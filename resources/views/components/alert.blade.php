<div>
    @if(session()->has('message'))
    {{$slot}}
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{session()->get('message')}} .
        
    </div>
    {{session()->forget('message')}}

    @elseif(session()->has('error'))

    {{$slot}}
    <div class="alert alert-danger alert-dismissible" style="background-color: #f8d7da; border-color:#f5c6cb; color:#721c24; margin-bottom: 0px;">
      <strong>Error!</strong> {{session()->get('error')}} .
      

    </div>
    @endif

    @if($errors->any())
    <ul>
    <div class="alert alert-danger alert-dismissible" style="background-color: #f8d7da; border-color:#f5c6cb; color:#721c24; margin-bottom: 0px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

            @foreach($errors->all() as $error)
            <li style="list-style: none;">{{$error}}</li>
            @endforeach

    </div>
    </ul>
    @endif

    @if(session()->has('delete'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Deleted!</strong> {{session()->get('delete')}} .
        
      </div>
    @endif
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{session()->get('success')}} .
        
      </div>
    @endif
</div>