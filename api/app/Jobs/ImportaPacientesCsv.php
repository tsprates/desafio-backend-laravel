<?php

namespace App\Jobs;

use App\Http\Requests\StorePacienteRequest;
use App\Models\Paciente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ImportaPacientesCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $csvFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($csvFile)
    {
        $this->csvFile = storage_path('app/public/csv/' . $csvFile);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!is_file($this->csvFile)) {
            Log::error(sprintf('%s não é um csv válido.', $this->csvFile));
            return;
        }

        $rules = (new StorePacienteRequest())->rules();
        $file = fopen($this->csvFile, 'r');
        $fields = fgetcsv($file);
        while (($data = fgetcsv($file)) !== false) {
            $input = array_combine($fields, $data);    
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $paciente = Paciente::create($input);
                $paciente->endereco()->create($input);
            }
        }
        fclose($file);
    }
}
