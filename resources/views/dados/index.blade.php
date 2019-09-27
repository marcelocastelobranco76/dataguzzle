{{-- \resources\views\dados\index.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="container">
        <h1>Vigências e Valores</h1>

        <!-- Aqui mostra todas as mensagens -->
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Vigência</td>
                    <td>Valor</td>
                </tr>
            </thead>
            <tbody>
            @foreach($dados as $key => $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->vigencia}}</td>
                    <td>{{ $value->valor_mensal}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $dados->links() }}

        <hr>
      <pre>{{ print_r(json_decode(json_encode((array)$arrayDados),true),true)   }}</pre>
</div>

@endsection
