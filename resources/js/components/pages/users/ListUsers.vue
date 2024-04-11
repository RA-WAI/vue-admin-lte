<script setup>
import axios from 'axios';
import { onMounted, ref, reactive } from 'vue';
const users = ref([]);

const getUser = () => {
    axios.get('/api/users')
    .then( (response) => {
        users.value = response.data;
    })
    .catch(error => { console.log(error) });
}

const form = reactive({
    name: '',
    email: '',
    password: '',
})

const createUser = () => {
    axios.post('/api/users', form)
    .then((response) => {
        users.value.unshift(response.data);
        form.name = "";
        form.email = "";
        form.password = "";
        $("#createUserModel").modal('hide');
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
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-right mb-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModel" data-whatever="@mdo">Create New User</button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr  v-for="(user, i) in users" :key="user.id">
                                <th scope="row">{{ ++i }}</th>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Create User Model -->
    <div class="modal fade" id="createUserModel" tabindex="-1" role="dialog" aria-labelledby="createUserModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModelLabel">Create New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="name" class="col-form-label">User Name</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email</label>
                            <input v-model="form.email" type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-form-label">Password</label>
                            <input v-model="form.password" type="password" name="password" id="password" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Cancel</button>
                    <button @click="createUser" type="button" class="btn btn-primary">Create</button>
                </div>
            </div>
        </div>
    </div>
</template>
