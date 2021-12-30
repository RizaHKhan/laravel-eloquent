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
```
