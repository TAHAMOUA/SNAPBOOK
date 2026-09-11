<x-app-layout>
    <div class="dash-wrap" style="max-width:760px;">

        <div class="admin-top">
            <div class="admin-title">Profile</div>
        </div>

        <div class="space-y-6">
            <div class="apanel">
                <div class="ap-h">
                    <div class="ap-t">Profile Information</div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="apanel">
                <div class="ap-h">
                    <div class="ap-t">Update Password</div>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="apanel">
                <div class="ap-h">
                    <div class="ap-t">Delete Account</div>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>
</x-app-layout>