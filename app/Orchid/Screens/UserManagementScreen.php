<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Color;

class UserManagementScreen extends Screen
{
    public $name = 'Manajemen User';

    public function query(): iterable
    {
        return [
            'users' => User::paginate()
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Tambah User')
                ->method('create')
                ->type(Color::PRIMARY())
        ];
    }

    public function layout(): iterable
    {
        return [

            Layout::table('users', [

                TD::make('id','ID'),

                TD::make('name','Nama'),

                TD::make('email','Email'),

                TD::make('created_at','Dibuat'),

                TD::make('Aksi')
                    ->render(function(User $user){

                        return

                        Button::make('Edit')
                            ->method('edit')
                            ->parameters(['id'=>$user->id])
                            ->type(Color::WARNING())

                        .

                        Button::make('Hapus')
                            ->method('delete')
                            ->parameters(['id'=>$user->id])
                            ->confirm('Yakin hapus user ini?')
                            ->type(Color::DANGER());

                    }),

            ]),

        ];
    }

    public function create(Request $request)
    {
        User::create([
            'name' => 'User Baru',
            'email' => 'user'.rand(1,999).'@mail.com',
            'password' => bcrypt('password')
        ]);

        Toast::info('User berhasil dibuat');
    }

    public function delete(Request $request)
    {
        User::findOrFail($request->id)->delete();

        Toast::warning('User dihapus');
    }
}