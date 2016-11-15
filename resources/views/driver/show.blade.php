@extends('page')

@section('content')

    <h1>
        @include('nation.image', ['nation' => $driver->nation])
        {{ $driver->name }}
    </h1>

    <div>

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#dirt-rally" aria-controls="home" role="tab" data-toggle="tab">
                    Dirt Rally
                </a>
            </li>
            @foreach(\RacesCategories::getList() AS $navCat)
                <li role="presentation">
                    <a href="#races-{{ $navCat->id }}" aria-controls="profile" role="tab" data-toggle="tab">
                        {{ $navCat->name }}
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="dirt-rally">
                @include('dirt-rally.driver.show')
            </div>

            @foreach(\RacesCategories::getList() AS $navCat)
                <div role="tabpanel" class="tab-pane" id="races-{{ $navCat->id }}">
                    @include('races.driver.show', ['category' => $navCat])
                </div>
            @endforeach
        </div>

    </div>

@endsection