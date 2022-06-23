<ul>
    @foreach ($tickets as $ticket)
        <li>{{ $ticket->category_id }} | {{ $ticket->company_id }}</li> 
    @endforeach
</ul>