<?php

namespace Database\Seeders;

use App\Models\ArchiveClassification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArchiveClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [];

        $files = glob(database_path('seeders/archive-classifications/*.php'));

        sort($files);

        foreach ($files as $file) {
            $items = array_merge($items, require $file);
        }

        $map = [];

        foreach ($items as $item) {
            $parentId = null;

            if (! empty($item['parent_full_code'])) {
                $parentId = $map[$item['parent_full_code']] ?? null;
            }

            // name will take from $item['name'] before parenthesis
            // example: "Surat Masuk (SM)" will take "Surat Masuk" as name
            // text inside parenthesis will take as description, example: "Surat Masuk (SM)" will take "SM" as description
            if (strpos($item['name'], '(') !== false) {
                $name = trim(substr($item['name'], 0, strpos($item['name'], '(')));
                $description = trim(substr($item['name'], strpos($item['name'], '(') + 1, -1));

                $item['name'] = $name;
                $item['description'] = $description;
            }

            $classification = ArchiveClassification::query()->updateOrCreate(
                ['full_code' => $item['full_code']],
                [
                    'uuid' => Str::uuid(),
                    'parent_id' => $parentId,
                    'code' => $item['code'],
                    'name' => $item['name'],
                    'description' => $item['description'] ?? null,
                    'level' => $item['level'],
                    'is_active' => true,
                ]
            );

            $map[$item['full_code']] = $classification->id;
        }
    }
}
