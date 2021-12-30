# Laravel Database Queries

Some basic queries:

```
    $users = DB::table('users')->get();
     $users = DB::table('users')->pluck('email');
     $user = DB::table('users')->where('name', 'Arely Prosacco')->value('email');
     $user = DB::table('users')->find(1);

     $comments = DB::table('comments')->select('content as comment_content')->get();
     $comments = DB::table('comments')->select('user_id')->distinct()->get();
     $comments = DB::table('comments')->count();
     $comments = DB::table('comments')->max('user_id');
     $comments = DB::table('comments')->sum('user_id');
     $comments = DB::table('comments')->where('content', 'content')->exists();
     $comments = DB::table('comments')->where('content', 'content')->doesntExist();
     dump($comments);

    $results = DB::table('rooms')->get();
    $results = DB::table('rooms')->where('price', '<', 200)->get();
    $results = DB::table('rooms')->where([['price', '<', 400], ['room_size', '<', 2]])->get();
    $results = DB::table('rooms')->where('room_size', 2)->orWhere('price', '<', 400)->get();
    $results = DB::table('rooms')->where('price', '<', 400)->orWhere(function ($query) {
        $query->where('room_size', '>', 1)->where('room_size', '<', 4);
    })->get();

     $results = DB::table('rooms')->whereBetween('room_size', [1, 3])->get(); */
     $results = DB::table('rooms')->whereNotIn('room_size', [1, 3])->get(); */
     $results = DB::table('rooms')->whereNotIn('room_size', [1, 3]) */
     whereNull('column')
     whereDate('created_at', '2020-03-12')
     whereMonth('created_at', '5')
     whereDay('create_at', '13')
     whereYear('create_at', '2020')
     whereTime('created_at', '=', '12:25:10')
     whereColumn('column1', '>', 'column2')
     whereColumn([
     [ 'first_name', '=', 'last_name' ],
     [ 'update_at', '>', 'created_at' ]
     ])
     ->get();

    $results = DB::table('users')
        ->whereExists(function ($query) {
            $query->select('id')
                ->from('reservations')
                ->whereRaw('reservations.user_id = users.id')
                ->where('check_in', '=', '2020-05-30')
                ->limit(1);
        })->get();

To compare json objects:

    $users = DB::table('users')->whereJsonContains('meta->skills', 'Wordpress')->get();
    $users = DB::table('users')->where('meta->settings->site_language', 'en')->get();

    $result = DB::table('comments')->where('content', 'like', '%repellendus%')->get();

    // Pagination
    $results = DB::table('comments')->paginate(3);
    $results = DB::table('comments')->simplePaginate(3);


    $results = DB::table('comments')->select(DB::raw('count(user_id) as number_of_comments, users.name'))
        ->join('users', 'users.id', '=', 'comments.user_id')
        ->groupBy('user_id', 'name')
        ->get();

    $result = DB::table('comments')
        ->orderByRaw('updated_at - created_at DESC')
        ->get();

    $result = DB::table('users')
        ->selectRaw('LENGTH(name) as name_length, name')
        ->orderByRaw('LENGTH(name) DESC')
        ->get();

    $result = DB::table('users')
        ->orderBy('name', 'desc')
        ->get();

    $result = DB::table('users')
        ->latest()
        ->first();

    $result = DB::table('users')
        ->inRandomOrder()
        ->first();

    $result = DB::table('comments')
        ->selectRaw('count(id) as number_of_5star_comments, rating')
        ->groupBy('rating')
        ->where('rating', '=', 5) // can also use having()
        ->get();

    $result = DB::table('comments')
        ->skip(2) // or offset()
        ->take(5)
        ->get();

    $room_id = 1;
    $result = DB::table('reservations')
        ->when($room_id, function ($query, $room_id) {
            return $query->where('room_id', $room_id);
        })
        ->get();

    $sortBy = null;
    $result = DB::table('rooms')
        ->when($sortBy, function ($query, $sortBy) { // executed only if $orderBy is not bull
            return $query->orderBy($sortBy);
        }, function ($query) { // executed if $sortBy is null
            return $query->orderBy('price');
        })
        ->get();

    $result = DB::table('comments')
        ->orderBy('id')
        ->chunk(2, function ($comments) {
            foreach ($comments as $comment) {
                if ($comment->id == 5) {
                    return false;
                }
            }
        });

    $result = DB::table('comments')->orderBy('id')->chunkById(5, function ($comments) {
        foreach ($comments as $comment) {
            DB::table('comments')
                ->where('id', $comment->id)
                ->update(['rating' => null]);
        }
    });
```

## JOIN SQL Clauses: from simple to advanced queries

```
$result = DB::table('reservations')
    ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
    ->join('users', 'reservations.user_id', '=', 'users.id')
    ->where('rooms.id', '>', 3)
    ->where('users.id', '>', 1)
    ->get();

// Same as above but easier to read
$result = DB::table('reservations')
    ->join('rooms', function ($join) {
        $join->on('reservations.room_id', '=', 'rooms.id')
            ->where('rooms.id', '>', 3);
    })->join('users', function ($join) {
        $join->on('reservations.user_id', '=', 'users.id')
            ->where('users.id', '=', 1);
    })->get();

// Another way is to use sub queries
$rooms = DB::table('rooms')->where('id', '>', 3);
$users = DB::table('users')->where('id', '>', 1);
$result = DB::table('reservations')
    ->joinSub($rooms, 'rooms', function ($join) {
        $join->on('reservations.room_id', '=', 'rooms.id');
    })
    ->joinSub($users, 'users', function ($join) {
        $join->on('reservations.user_id', '=', 'users.id');
    })
    ->get();

$result = DB::table('rooms')
    ->leftJoin('reservations', 'rooms.id', '=', 'reservations.room_id')
    ->leftJoin('cities', 'reservations.city_id', '=', 'cities.id')
    ->selectRaw('room_size, cities.name, count(reservations.id) as reservations_count')
    ->groupBy('room_size', 'cities.name')
    ->orderByRaw('count(reservations.id) DESC')
    ->get();


// Good for generating reports like so:
$result = DB::table('rooms')
    ->crossJoin('cities')
    ->leftJoin('reservations', function ($join) {
        $join->on('rooms.id', '=', 'reservations.room_id')->on('cities.id', '=', 'reservations.city_id');
    })
    ->selectRaw('count(reservations.id) as reservations_count, room_size, cities.name')
    ->groupBy('room_size', 'cities.name')
    ->orderByRaw('rooms.room_size DESC')
    ->get();
```

## UNION Select Clause

`union` joins tables vertically, whereas in `join` tables are joined horizontally.

```
    $users = DB::table('users')
        ->select('name');

    $result = DB::table('cities')
        ->select('name')
        ->union($users)
        ->get();


    // Doesn't work
    $comments = DB::table('comments')
        ->select('rating as rating_or_room_id', 'id', DB::raw('"comments" as type_of_activity'))
        ->where('user_id', 2);

    $result = DB::table('reservations')
        ->select('room_id as rating_or_room_id', 'id', DB::raw('"reservations" as type_of_activity'))
        ->union($comments)
        ->where('user_id', 2)
        ->get();

```

## INSERT Statement

```
    DB::table('rooms')->insert([
        ['room_number' => 1, 'room_size' => 2, 'price' => 300, 'description' => 'fools bar'],
        ['room_number' => 2, 'room_size' => 3, 'price' => 1300, 'description' => 'expensive']
    ]);

    // use this if you need the id's back
    DB::table('rooms')->insertGetId([
        ['room_number' => 1, 'room_size' => 2, 'price' => 300, 'description' => 'fools bar'],
        ['room_number' => 2, 'room_size' => 3, 'price' => 1300, 'description' => 'expensive']
    ]);

```

## UPDATE Statement

```
    $result = DB::table('rooms')
        ->where('id', 1)
        ->update(['price' => 222]);
```

You can also update JSON objects by using arrows method

```

    $result = DB::table('users')
        ->where('id', 1)
        ->update(['meta->settings->site_language' => 'fr']);


```

Increment Method:
There is also a `decrement` method.

```
    $result = DB::table('rooms')
        ->increment('price', 20);
```

## DELETE Statement

```
    DB::table('rooms')->delete();
```

# Laravel Eloquent ORM basics

```
    $result = DB::table('rooms')->where('room_size', '=', 4)->get(); // QueryBuilder
    $result = Room::where('room_size', 4)->get(); // Eloquent
    $result = Room::where('price', '<', 200)->get(); // Eloquent
    $result = Room::get(); // Eloquent

```

Joining two tables. This only works if there is a foreign key on the comments table (`user_id`)

```
    $result = User::select('name', 'email')
        ->addSelect(['worst_rating' => Comment::select('rating')
            ->whereColumn('user_id', 'users.id')
            ->orderBy('rating', 'asc')
            ->limit(1)])
        ->get()->toArray();
```

Another example where we are ordering Users based on information from another table

```
    $result = User::orderByDesc(
        Reservation::select('check_in')
            ->whereColumn('user_id', 'users.id')
            ->orderBy('check_in', 'desc')
            ->limit(1)
    )->select('id', 'name')->get();
```

Chunking in Eloquent

```
    $result = Reservation::chunk(2, function ($reservations) {
        foreach ($reservations as $reservation) {
            echo $reservation->id;
        }
    });

```

### Cursor

Used instead of `get()`

```
    foreach (Room::cursor() as $reservation) {
        echo $reservation->id;
    }
```

Simple get requests:

```
    $result = User::find(1); // Single
    $result = User::all(); // all
    $result = User::findOrFail(1); // Fail if nothing found
    $result = User::max('id'); // get max id
    $result = User::find([1, 2, 3, 4]); // or with array

    $result = User::where('email', 'like', '%@%')->first(); // get the first item that matches this like criteria

    $result = User::where('email', 'like', '%@%')->firstOr(function(){
        User::where('id', 1)->update(['email' => 'email@email2.com']);
    }); // And with Or which allows the use of a function

    $result = User::where('email', 'like', '%@%')->get(); // get all items that match this like criteria
```

Adding global scopes

```
// First add a boot method in the model as follows:

class Comment extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('rating', function (Builder $builder) {
            $builder->where('rating', '>', 2);
        });
    }
}

// When using a query like
Comment::all(); // The where clause will be added automatically to the query

//If the user ever wants to override the globalscrope constraint use:

Comment::withoutGlobalScope('rating')->get();
```

## Many To Many Relationship

A many to many relationship needs a pivot table to work properly
