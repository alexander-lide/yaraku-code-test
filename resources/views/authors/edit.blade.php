@extends('layouts.master')
 
@section('title', 'Edit Author')
 
@section('content')
    <x-author-form type="edit" :$author />
@stop