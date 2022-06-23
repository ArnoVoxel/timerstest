<?php
    use App\Models\Company;
    use App\Models\Category;
?>
<form action="/create_timer" method="POST">
    {{ csrf_field() }}
    <label for="category">Choisissez une catégorie</label>
    <select name="category" id="category">
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->category_label }}</option>
        @endforeach
    </select>
    <br>
    <label for="company">quelle société ?</label>
    <select name="company" id="company">
        @foreach($companies as $company)
            <option value="{{ $company->id }}">{{ $company->company_label }}</option>
        @endforeach
    </select>
    <br>
    <input type="submit" value="Start Now">
</form>

<table>
    <thead>
        <th>numéro de timer </th>
        <th>debut du timer</th>
        <th>fin du timer</th>
        <th>client</th>
        <th>catégorie de timer</th>
    </thead>
    <tbody>
        @foreach($timers as $timer)
        <tr>
            <td>{{ $timer->id }}</td>
            <td>{{ $timer->started_at }}</td>
            <td>{{ $timer->ended_at }}</td>
            <td>{{ Company::find($timer->company_id)->company_label }}</td>
            <td>{{ Category::find($timer->category_id)->category_label }}</td>
        </tr>
        @endforeach
    </tbody>
</table>