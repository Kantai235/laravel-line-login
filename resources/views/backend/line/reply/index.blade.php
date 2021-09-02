@extends('backend.layouts.app')

@section('title', __('Reply Management'))

@section('breadcrumb-links')
    @include('backend.line.reply.includes.breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Reply Management')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.line.reply.create')"
                :text="__('Create Reply')"
            />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.line-reply-table />
        </x-slot>
    </x-backend.card>
@endsection
