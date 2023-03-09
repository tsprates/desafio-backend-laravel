<?php

namespace App\Jobs;

use App\Http\Requests\StorePacienteRequest;
use App\Models\Paciente;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class ImportaPacientesCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $csvFilePath;

    public function __construct($csvFileName)
    {
        $this->csvFilePath = storage_path('app/public/csv/' . $csvFileName);
    }

    public function handle()
    {
        try {
            $csv = Reader::createFromPath($this->csvFilePath, 'r');
            $csv->setHeaderOffset(0);
            $header = array_map('trim', $csv->getHeader());

            $rules = (new StorePacienteRequest())->rules();
            
            // TODO:
            unset($rules['foto']);

            foreach ($csv->getRecords() as $record) {
                $input = array_combine($header, $record);
                $validator = Validator::make($input, $rules);

                if ($validator->fails()) {
                    Log::info(json_encode([$record, $validator->errors()]));
                    continue;
                }
                
                $paciente = Paciente::create($input);
                $paciente->endereco()->create($input);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
