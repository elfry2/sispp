@extends('layouts.form')
@section('content')
<form action="{{ route($resource . '.applyPreferences') }}" method="post">
        @csrf
        <div class="mt-5">
            <b>Sorting</b>
            <div class="form-floating mt-3">
                <select name="order_column" class="form-select" id="orderColumnSelectInput" autofocus>
                    @foreach ($primary as $option)
                        <option value="{{ $option->value }}" @if (preference($resource . '.order.column') == $option->value) selected @endif>
                            {{ $option->label }}</option>
                    @endforeach
                </select>
                <label for="orderColumnSelectInput">Sort by</label>
            </div>
            <div class="form-floating mt-3">
                <select name="order_direction" class="form-select" id="orderDirectionSelectInput">
                    @foreach ([
                            (object) [
                                'value' => 'ASC',
                                'label' => 'Ascending'
                            ],
                            (object) [
                                'value' => 'DESC',
                                'label' => 'Descending'
                            ],
                        ] as $option)
                        <option value="{{ $option->value }}" @if (str(preference($resource . '.order.direction'))->upper() == $option->value) selected @endif>
                            {{ $option->label }}
                        </option>
                    @endforeach
                </select>
                <label for="orderDirectionSelectInput">Sorting direction</label>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn" type="submit"><i class="bi-pencil-square"></i><span class="ms-2">Save</span></button>
        </div>
    </form>
@endsection
