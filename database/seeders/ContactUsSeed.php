<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactUsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = ['What is Lorem Ipsum?', 'Why do we use it?', 'Where does it come from?', 'Where can I get some?', 'default model text, and a search', 'and more recently with desktop publishing software like Aldus PageMaker including versions', 'type and scrambled it to make a type specimen book'];
        foreach ($subjects as $item) {
            if (ContactUs::withTrashed()->where('subject', $item)->count() == 0){
                $contact_us =
                    [
                        'name' => RandomString(),
                        'message' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum',
                        'email' => RandomString() . '@gmail.com',
                        'subject' => $item,
                    ];
                ContactUs::query()->insert($contact_us);
            }

        }

    }
}
