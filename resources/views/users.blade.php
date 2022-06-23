<ul>
    @foreach ($users as $user)
       <li>{{ $user->email }} | {{ $user->name }}</li> 
    @endforeach
</ul>