<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupSystem extends Command
{
    /**
     * O nome e a assinatura do comando.
     */
    protected $signature = 'system:backup {--disk=s3 : O disco de destino para o backup}';

    /**
     * A descrição do comando.
     */
    protected $description = 'Realiza o backup forense do Banco de Dados e dos Arquivos Anexos (Comprovantes)';

    /**
     * Execute o comando.
     */
    public function handle()
    {
        $this->info('Iniciando rotina de backup forense...');

        $date = now()->format('Y-m-d_H-i-s');
        $dbFilename = "backup_db_{$date}.sql";
        $filesFilename = "backup_files_{$date}.zip";
        $tempPath = storage_path('app/backup-temp');

        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        // 1. Backup do Banco de Dados (MySQL Dump)
        $this->line('Gerando dump do banco de dados...');
        
        $dbConfig = config('database.connections.mysql');
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($dbConfig['username']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['host']),
            escapeshellarg($dbConfig['database']),
            escapeshellarg($tempPath . DIRECTORY_SEPARATOR . $dbFilename)
        );

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Falha ao gerar dump do banco de dados.');
            return 1;
        }

        // 2. Backup dos Arquivos (Zip do Storage)
        $this->line('Compactando arquivos anexos (comprovantes)...');
        $storagePath = storage_path('app/public');
        $zipFile = $tempPath . DIRECTORY_SEPARATOR . $filesFilename;

        $zipCommand = sprintf('zip -r %s %s', escapeshellarg($zipFile), escapeshellarg($storagePath));
        $zipProcess = Process::fromShellCommandline($zipCommand);
        $zipProcess->run();

        // 3. Upload para o Disco Externo (S3 / Drive / Local)
        $disk = $this->option('disk');
        $this->line("Enviando backups para o disco: {$disk}...");

        try {
            Storage::disk($disk)->put("backups/{$date}/{$dbFilename}", file_get_contents($tempPath . DIRECTORY_SEPARATOR . $dbFilename));
            Storage::disk($disk)->put("backups/{$date}/{$filesFilename}", file_get_contents($zipFile));
            
            $this->info('Backup concluído e enviado com sucesso!');
        } catch (\Exception $e) {
            $this->error('Erro no envio do backup: ' . $e->getMessage());
        }

        // 4. Limpeza Local
        array_map('unlink', glob("{$tempPath}/*"));
        rmdir($tempPath);

        return 0;
    }
}
