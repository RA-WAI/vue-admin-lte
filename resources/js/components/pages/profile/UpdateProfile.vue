<script setup>
import axios from 'axios';
import { ref, onMounted, handleError } from 'vue';
import { useToastr } from '../../../toastr.js';

const form = ref({
    'email': '',
    'name': '',
    'role': '',
    'profile_image': '',
});
const toastr = useToastr();

const getUser = () => {
    axios.get('/api/profile')
        .then((response) => {

            form.value = response.data;

        })
        .catch((error) => {
            console.log(error);
        });
}
const profileImg = ref(null);

const openProfileImg = () => {
    profileImg.value.click();
}

const profileImageUrl = ref(null);
const handleProfileChange = (event) => {
    const file = event.target.files[0];
    profileImageUrl.value = URL.createObjectURL(file);

    const formData = new FormData();
    formData.append('profile_image', file);

    axios.post('/api/upload-profile-image', formData)
        .then((response) => {
            toastr.success(response.data.message);
        })
        .catch(error => {
            console.log(error);
        });
}

onMounted(() => {
    getUser();
});
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <input @change="handleProfileChange" ref="profileImg" type="file" class="d-none">
                                <img @click="openProfileImg" class="profile-user-img img-fluid img-circle"
                                :src="profileImageUrl ? profileImageUrl : form.profile_image"
                                alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center">{{ form.name }}</h3>
                            <p class="text-muted text-center">{{ form.role }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-9">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi adipisci suscipit et, earum eum a molestias tempore at error accusantium, debitis sapiente illo mollitia nam id est! Necessitatibus, ad sequi.
                            </p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .profile-user-img:hover {
        background-color: blue;
        cursor: pointer;
    }
</style>
