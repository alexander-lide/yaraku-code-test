@extends('layouts.master')
 
@section('title', 'Export List')
 
@section('content')
    <div class="card card-body bg-light mb-3">
        <form method="POST" action="/list/">
            @csrf

            <div class="form-group">
                <div class="row">
                    <label class="col-form-label">Show titles and/or authors in list <small>(Only authors that have written books will appear on the list)</small></label>
                </div>
                <input type="checkbox" name="include[title]"> Titles
                <input type="checkbox" name="include[author]"> Authors
                @if($errors->has('include'))
                    <div class="alert alert-danger mt-3">{{ $errors->first('include') }}</div>
                @endif
            </div>
            <div class="form-group row py-2">
                <label for="type" class="col-form-label">Download as CSV or XML file</label>
                <div>
                    <select name="type">
                        <option value="csv">CSV</option>
                        <option value="xml">XML</option>
                    </select>
                    @if($errors->has('type'))
                        <div class="alert alert-danger mt-3">{{ $errors->first('type') }}</div>
                    @endif
                </div>
            </div>

            <input class="btn btn-primary" type="submit" value="Download">
        </form>
    </div>
@stop
