@extends('frontend.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3 aside unibee-aside">
                @include('frontend.user.includes.aside')
            </div>
            <div class="col-md-9 profile-private">
                <div class="wrap_main">
                    <section>
                        <div class="panel unibee-box">
                            <div class="panel-heading notifi-head">
                                <h3 class="panel-title">
                                    <span class="title">Danh sách link chia sẻ</span>
                                </h3>
                            </div>
                            <div class="panel-body no-padding"></div>
                        </div>
                        <div class="panel">
                            <div class="panel-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Link chia sẻ</th>
                                            <th style="min-width: 105px">% chiết khấu</th>
                                            <th style="min-width: 150px">Số người sử dụng</th>
                                            <th style="min-width: 80px">Thao tác</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($links)
                                            @foreach ($links as $link)
                                                <tr>
                                                    <td>
                                                        <a 
                                                        href="{{ $link->course->get_public_view_link() }}" 
                                                        target="_blank"
                                                        style="color: #333;text-decoration: none">
                                                            {{ $link->course->get_public_view_link().'?code='.$link->id }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $link->discount }}</td>
                                                    <td>{{ $link->used_count}}</td>
                                                    <td class="text-center">
                                                        <button 
                                                        type="button"
                                                        class="btn btn-xs btn-danger action-del-link"
                                                        data-src="{{ route('frontend.link.delete',['id' => $link->id]) }}">
                                                            <i 
                                                            class="fa fa-trash" 
                                                            data-toggle="tooltip" data-placement="top" data-original-title="Xóa"
                                                            style="margin: 0"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection