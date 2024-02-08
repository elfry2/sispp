<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    protected const resource = 'users';

    protected const title = "Pengguna";

    protected const queryColumnNames = [
        'name',
        'username',
        'email',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $primary = '\App\Models\\' . str(self::resource)->singular()->title();

        $data = (object) [
            'resource' => self::resource,
            'title' => self::title,
            'primary'
            => (new $primary)->orderBy(
                preference(self::resource . '.order.column', 'id'),
                preference(self::resource . '.order.direction', 'ASC')
            ),
        ];


        // protected const queryColumnNames = [
        //     'name',
        //     'username',
        //     'email',
        // ];

        if (!empty(request('q'))) {

            foreach (self::queryColumnNames as $index => $columnName) {

                $method = 'where';

                if($index > 0) $method = 'orWhere';

                $data->primary = $data->primary
                    ->$method($columnName, 'like', '%' . request('q') . '%');
            }
        }

        if (!empty(preference(self::resource . '.filters.levelId'))) {
            $data->primary
            = $data->primary->where(
                'level_id',
                preference(self::resource . '.filters.levelId')
            );
        }

        $data->primary = $data->primary
        ->paginate(config('app.rowsPerPage'))->withQueryString();

        return view(self::resource . '.index', (array) $data);
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'primary' => Level::all()
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $isByAdmin = Auth::id() && (Auth::user()->level->name == 'Administrator');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => ['required', 'alpha_num:ascii', 'max:255', 'unique:users'],
            'avatar' => ['nullable', 'image', 'max:10240'],
            'phone_number' => ['nullable', 'string', 'numeric', 'digits_between:0,15'],
            'address' => ['nullable', 'string'],
            'level_id' => [
                'integer',
                'exists:levels,id',
                Rule::requiredIf(fn () => $isByAdmin)
            ],
        ]);

        $avatarPath = null;

        if (isset($request->avatar)) {
            $avatarPath = $request->file('avatar')->store('avatars');
        }

        if ($isByAdmin) {
            $levelId = $request->level_id;
        }

        $primary = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'avatar' => $avatarPath,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'level_id' => $levelId,
        ]);

        event(new Registered($primary));

        if ($isByAdmin)
            return redirect(route(self::resource . '.index'))
                ->with('message', (object) [
                    'type' => 'success',
                    'content' => self::title . ' ditambahkan.'
                ]);

        Auth::login($primary);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $primary = $user;

        $data = (object) [
            'resource' => self::resource,
            'title' => 'Sunting ' . str(self::title)->lower(),
            'primary' => $primary,
            'secondary' => Level::all(),
        ];

        return view(self::resource . '.edit', (array) $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $primary = $user;

        if ($request->method === 'removeAvatar') {
            $primary->update(['avatar' => null]);

            return redirect()->back()
                ->with('message', (object) [
                    'type' => 'success',
                    'content' => 'Avatar deleted.'
                ]);
        }

        $validated = (object) $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|alpha_num:ascii|max:255',
            'email' => 'required|max:255|email',
            'password' => 'nullable|max:255|confirmed',
            'avatar' => 'nullable|image|max:10240',
            'phone_number' => ['nullable', 'numeric', 'digits_between:0,15'],
            'address' => ['nullable', 'string'],
            'suspended_until_date' => [
                'nullable',
                'date',
                Rule::requiredIf(fn () => !empty($request->suspended_until_time))
            ],
            'suspended_until_time' => 'nullable|max:9',
            'level_id' => 'required|integer|exists:levels,id',
        ]);

        $primary->name = $validated->name;

        if ($validated->username != $primary->username) {
            $request->validate([
                'username' => 'unique:users',
            ]);

            $primary->username = $validated->username;
        }

        if ($validated->email != $primary->email) {
            $request->validate([
                'email' => 'unique:users',
            ]);

            $primary->email = $validated->email;
        }

        $primary->level_id = $validated->level_id;

        $primary->suspended_until
            = !$validated->suspended_until_date
            ? null
            : $validated->suspended_until_date
            . ' ' . ($validated->suspended_until_time ?: '00:00:00');

        if (isset($validated->avatar)) {
            Storage::delete($primary->avatar ?: '');

            $avatarPath = $request->file('avatar')->store('avatars');

            $primary->avatar = $avatarPath;
        }

        if (isset($validated->password)) {
            $primary->password = Hash::make($validated->password);
        }

        if (isset($validated->phone_number)) {
            $primary->phone_number = $validated->phone_number;
        }

        if (isset($validated->address)) {
            $primary->address = $validated->address;
        }

        $primary->save();

        return redirect()->back()
            ->with('message', (object) [
                'type' => 'success',
                'content' => self::title . ' disunting.'
            ]);
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(User $user)
    {
        $primary = $user;

        $data = (object) [
            'resource' => self::resource,
            'title' => 'Hapus ' . str(self::title)->lower(),
            'primary' => $primary
        ];

        return view(self::resource . '.delete', (array) $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $primary = $user;

        if ($primary->id == Auth::id())
            return redirect(route(self::resource . '.index'))
                ->with('message', (object) [
                    'type' => 'danger',
                    'content' => 'Tidak bisa menghapus pengguna yang sama',
                ]);

        Storage::delete($primary->avatar ?: '');

        $primary->preferences()->delete();

        $primary->delete();

        return redirect(route(self::resource . '.index'))
            ->with('message', (object) [
                'type' => 'success',
                'content' => self::title . ' dihapus.'
            ]);
    }

    public function deleteAvatar(User $user)
    {
        $primary = $user;

        $data = (object) [
            'resource' => self::resource,
            'primary' => $primary,
            'title' => 'Delete avatar',
        ];

        return view(self::resource . '.delete-avatar', (array) $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAvatar(User $user, Request $request)
    {
        $primary = $user;

        Storage::delete($primary->avatar ?: '');

        $primary->update(['avatar' => null]);

        return redirect($request->redirectTo ?: route(
            self::resource . '.edit',
            [Str::singular(self::resource) => $primary]
            ))->with('message', (object) [
                'type' => 'success',
                'content' => 'Avatar deleted.'
            ]);
    }

    /**
     * Show the form for editing the preferences for the listing of the resource.
     */
    public function preferences()
    {
        $data = (object) [
            'resource' => self::resource,
            'title' => str(self::resource)->title() . ' preferences',
            'primary' => Schema::getColumnListing(self::resource),
        ];

        $data->primary = collect($data->primary)->map(function ($element) {
            return (object) [
                'value' => $element,
                'label' => str($element)->headline(),
            ];
        });

        $data->secondary = Level::all();

        return view(self::resource . '.preferences', (array) $data);
    }

    /**
     * Update the preferences for the listing of the resource in storage.
     */
    public function applyPreferences(Request $request)
    {
        $validated = (object) $request->validate([
            'order_column' => 'required|max:255',
            'order_direction' => 'required|max:255',
            'level_id' => ['nullable', Rule::in(Level::pluck('id'))]
        ]);

        foreach ([
            [self::resource . '.order.column' => $validated->order_column],
            [self::resource . '.order.direction' => $validated->order_direction],
            [self::resource . '.filters.levelId' => $validated->level_id],
        ] as $preference) {
            preference($preference);
        }

        return redirect(route(self::resource . '.index'))
            ->with('message', (object) [
                'type' => 'success',
                'content' => 'Preferences updated.'
            ]);
    }

    /**
     * Show the form for searching the resource.
     */
    public function search()
    {
        $data = (object) [
            'resource' => self::resource,
            'title' => 'Search ' . str(self::resource)->title()->lower()
        ];

        return view(self::resource . '.search', (array) $data);
    }
}
