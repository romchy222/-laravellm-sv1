<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ImportUsersFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import {file} {--role=student}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $role = $this->option('role');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Importing users from: {$filePath}");
        $this->info("Default role: {$role}");

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        $imported = 0;
        $skipped = 0;
        $errors = [];

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            // Validate data
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'nullable|string|min:8',
                'phone' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'email' => $data['email'] ?? 'unknown',
                    'errors' => $validator->errors()->all(),
                ];
                $skipped++;
                continue;
            }

            try {
                // Create user
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'password' => Hash::make($data['password'] ?? 'password123'),
                    'status' => 'active',
                ]);

                // Assign role
                $user->assignRole($role);

                $imported++;
                $this->line("✓ Imported: {$data['email']}");
            } catch (\Exception $e) {
                $errors[] = [
                    'email' => $data['email'],
                    'errors' => [$e->getMessage()],
                ];
                $skipped++;
            }
        }

        fclose($file);

        $this->newLine();
        $this->info("Import completed!");
        $this->info("Imported: {$imported}");
        $this->warn("Skipped: {$skipped}");

        if (count($errors) > 0) {
            $this->newLine();
            $this->error("Errors:");
            foreach ($errors as $error) {
                $this->line("  {$error['email']}: " . implode(', ', $error['errors']));
            }
        }

        return 0;
    }
}
