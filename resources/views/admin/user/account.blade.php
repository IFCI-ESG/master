<!-- resources/views/user/account.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>My Account</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.account.update') }}" method="POST">
                        @csrf

                        <!-- Name Field -->
                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <!-- PAN -->
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">PAN</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $user->pan }}" readonly>
                            </div>
                        </div>

                        <!-- Contact Person -->
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Contact Person</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $user->contact_person }}" readonly>
                            </div>
                        </div>

                        <!-- Designation -->
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Designation</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $user->designation }}" readonly>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Mobile</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $user->mobile }}" readonly>
                            </div>
                        </div>

                        <!-- IFSC Code -->
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">IFSC Code</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $user->ifsc_code }}" readonly>
                            </div>
                        </div>

                        <!-- Full Address -->
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Full Address</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $user->full_address }}" readonly>
                            </div>
                        </div>

                        <!-- Pincode -->
                        <div class="form-group row mb-3">
                            <label class="col-md-4 col-form-label text-md-right">Pincode</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $user->pincode }}" readonly>
                            </div>
                        </div>

                        <!-- Reset Password Checkbox -->
                        <div class="form-group row mb-3">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <input type="checkbox" id="reset_password" name="reset_password"> <label for="reset_password">Reset Password</label>
                            </div>
                        </div>

                       <!-- New Password Field -->
                       <div class="form-group row mb-3">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">New Password</label>
                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control" name="new_password">
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group row mb-3">
                            <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation">
                            </div>
                        </div>


                        <div class="form-group row mb-3">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Popup -->
<div id="confirm-password-change" class="modal" tabindex="-1" role="dialog" aria-labelledby="confirm-password-change-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-password-change-label">Confirm Password Change</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to change your password?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-change">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('reset_password').addEventListener('change', function() {
        document.getElementById('password-fields').style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('confirm-change').addEventListener('click', function() {
        document.getElementById('password-change-form').submit();
    });
</script>
@endsection
