@extends('welcome')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Регистрация</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('signup.post') }}">
                            {{ csrf_field() }}


                            <div class="form-group{{ Session::has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if (Session::has('email'))
                                    <span class="help-block">
                                        <strong>{{ Session::get('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Регистрация
                                    </button>
                                    
                                    @if (Session::has('message'))
                                    <span class="help-block">
                                        <strong>{{ Session::get('message') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
