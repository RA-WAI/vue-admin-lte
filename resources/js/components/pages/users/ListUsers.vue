<script setup>
import axios from 'axios';
import { onMounted, ref, reactive } from 'vue';
import { Form, Field } from 'vee-validate';
import * as yup from 'yup';

const users = ref([]);

const getUser = () => {
    axios.get('/api/users')
    .then( (response) => {
        users.value = response.data;
    })
    .catch(error => { console.log(error) });
}

const createUser = (values, { resetForm }) => {
    axios.post('/api/users', values)
        .then((response) => {
            users.value.unshift(response.data);
            $("#userFormModel").modal('hide');
            resetForm();
    });
}

const editUser = () => {
    $('#userFormModel').modal('show');
}

const schema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(8),
});

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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userFormModel" data-whatever="@mdo">Create New User</button>
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
                                <td>
                                    <a @click.prevent="editUer(user)">
                                        <i class="fa fa-edit text-warning "></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Create User Model -->
    <div class="modal fade" id="userFormModel" tabindex="-1" role="dialog" aria-labelledby="userFormModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userFormModelLabel">Create New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form @submit="createUser" :validation-schema="schema" v-slot="{errors}">
                    <div class="modal-body">

                            <div class="form-group">
                                <label for="name" class="col-form-label">User Name</label>
                                <Field name="name" type="text" class="form-control" id="name" :class="{'is-invalid' : errors.name}"/>
                                <span class="invalid-feedback">{{ errors.name }}</span>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email</label>
                                <Field name="email" type="email" id="email" class="form-control" :class="{'is-invalid' : errors.email}" />
                                <span class="invalid-feedback">{{ errors.email }}</span>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-form-label">Password</label>
                                <Field type="password" name="password" id="password" class="form-control" :class="{'is-invalid' : errors.password}" />
                                <span class="invalid-feedback">{{ errors.password }}</span>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
