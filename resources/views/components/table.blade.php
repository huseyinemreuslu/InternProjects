@extends('welcome')
@section('content')

<h3>{{$typed->type_name}}</h3>

<!-- Vue Table Components -->
<word-table :languages="{{ $languages }}" :type-id="{{ $typed }}" :list="{{ json_encode($list->toArray()['data']) }}" ></word-table>

{{ $list->links() }}
     
@endsection