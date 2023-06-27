<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'is_superAdmin' => true,
            'password' => bcrypt('superadmin'),
        ]);
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'is_admin' => true,
            'password' => bcrypt('admin'),
        ]);
        DB::table('users')->insert([
            'name' => 'Reviewer',
            'username' => 'reviewer',
            'is_reviewer' => true,
            'password' => bcrypt('reviewer'),
        ]);

        DB::table('questionaires')->insert([
            'name' => 'QA1',
            'question' => 'Apakah pernyataan mengenai objective of the research jelas?',
            'pos_answer' => 'Iya, objective of the research telah dijelaskan dengan baik dan jelas',
            'net_answer' => 'Objective of the research sudah ada namun tidak secara jelas dijelaskan (partial)',
            'neg_answer' => 'Tidak, Objective of the research tidak dijelaskan',
        ]);
        DB::table('questionaires')->insert([
            'name' => 'QA2',
            'question' => 'Apakah penelitian memperkenalkan deskripsi rinci tentang solusi atau pendekatan yang diusulkan?',
            'pos_answer' => 'Iya, solusi atau pendekatan yang diusulkan telah dijelaskan dengan baik dan jelas',
            'net_answer' => 'Sebagian, jika Anda ingin mengetahui lebih dalam mengenai pendekatan atau solusi, Anda harus membaca referensi',
            'neg_answer' => 'Tidak, solusi dan pendekatan yang diusulkan tidak terlihat.',
        ]);
        DB::table('questionaires')->insert([
            'name' => 'QA3',
            'question' => 'Apakah solusi atau pendekatan yang diusulkan tervalidasi?',
            'pos_answer' => 'Ya, digunakan dengan contoh case study',
            'net_answer' => 'Hal itu sebagian divalidasi di laboratorium, atau hanya sebagian dari proposal yang divalidasi',
            'neg_answer' => 'Tidak, Hal tersebut tidak ada validasi',
        ]);
        DB::table('questionaires')->insert([
            'name' => 'QA4',
            'question' => 'Apakah penelitian menyajikan pendapat atau sudut pandang dari penulis?',
            'pos_answer' => 'Ya, terdapat unsur tersebut.',
            'net_answer' => 'Sebagian karena pekerjaan utama yang sesuai sudah dijelaskan, dan artikel ini sudah masuk ke specific context',
            'neg_answer' => 'Tidak, Paper ini berdasarkan dari hasil sebuah research',
        ]);
        DB::table('questionaires')->insert([
            'name' => 'QA5',
            'question' => 'Apakah penelitian ini telah dikutip dalam publikasi ilmiah lainnya?',
            'pos_answer' => 'Iya, lebih dari lima paper ilmiah lainya yang mensitasi paper ini.',
            'net_answer' => 'Sebagian, ada antara satu atau lima paper penelitian ilmiah lainnya yang mensitasi paper ini',
            'neg_answer' => 'Tidak. tidak ada satupun yang mensitasi penelitian ini',
        ]);

    }
}
