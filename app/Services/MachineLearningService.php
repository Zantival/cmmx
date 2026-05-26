<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class MachineLearningService
{
    /**
     * Finds the available python executable on the host.
     */
    private function getPythonExecutable(): string
    {
        // Try python3 first, then python
        $options = ['python3', 'python'];
        foreach ($options as $option) {
            $process = new Process([$option, '--version']);
            try {
                $process->run();
                if ($process->isSuccessful()) {
                    return $option;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        return 'python3'; // Fallback
    }

    /**
     * Run the Python ML Engine to get a generic health score.
     * 
     * @param array $data Input data/metrics as array
     * @return array Decoded JSON response
     */
    public function analyzeCompanyHealth(array $data)
    {
        $scriptPath = base_path('scripts/python/analytics_engine.py');
        $jsonData = json_encode($data);
        $python = $this->getPythonExecutable();

        $process = new Process([$python, $scriptPath, $jsonData]);
        
        try {
            $process->mustRun();
            $output = trim($process->getOutput());
            
            $decoded = json_decode($output, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('ML Service JSON Decode Error: ' . json_last_error_msg() . ' | Output: ' . $output);
                return ['error' => 'Invalid output format from engine.', 'recommendation' => 'FIX_REQUIRED'];
            }

            return $decoded;
        } catch (ProcessFailedException $e) {
            Log::error('ML Service Process Failed: ' . $e->getMessage() . ' | Stderr: ' . $process->getErrorOutput());
            return [
                'error' => 'Failed to run ML engine.', 
                'details' => str($process->getErrorOutput())->limit(100),
                'recommendation' => 'CHECK_ENVIRONMENT'
            ];
        }
    }
}
