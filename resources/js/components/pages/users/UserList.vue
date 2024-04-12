<script setup>
import axios from 'axios';
import { onMounted, ref, reactive, watch } from 'vue';
import { Form, Field } from 'vee-validate';
import * as yup from 'yup';
import { useToastr } from '../../../toastr.js';
import UserListItem from "./UserListItem.vue";
import { debounce } from "lodash";

const users = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);
const toastr  = useToastr();


const getUser = () => {
    axios.get('/api/users')
    .then( (response) => {
        users.value = response.data;
    })
    .catch(error => { console.log(error) });
}

const createUser = (values, { resetForm, setErrors }) => {
    axios.post('/api/users', values)
        .then((response) => {
            users.value.unshift(response.data);
            $("#userFormModel").modal('hide');
            resetForm();
            toastr.success("User created successfully")
    }).catch((error) => {
        if (error.response.data.errors) {
            setErrors(error.response.data.errors);
        }
    });
}

const addUser = () => {
    editing.value = false;
    $('#userFormModel').modal('show');
}

const userEdit = (user) => {

    editing.value = true;
    form.value.resetForm();

    $('#userFormModel').modal('show');

    form.value =  formValues.value = {
        id: user.id,
        name: user.name,
        email: user.email,
    };

}

const createUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().required().min(8),
});

const editUserSchema = yup.object({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string()
        .when((password, schema) => {

            return (password[0] != undefined && password[0].length >= 1) ?
                    schema.required().min(8) : schema.nullable();
        }),
});

const updateUser = (values, {setErrors}) => {
    axios.put('/api/users/' + formValues.value.id, values)
        .then((response) => {
            const index = users.value.findIndex( user => user.id === response.data.id );
            users.value[index] = response.data;
            $('#userFormModel').modal('hide');
            toastr.success("User updated successfully")
        }).catch((error) => {
            if (error.response.data.errors) {
                setErrors(error.response.data.errors);
            }
        });
}

const handleSubmit = (values, actions) => {

    if (editing.value) {
        updateUser(values, actions);
    } else {
        createUser(values, actions);
    }
}


const userDeleted = (userId) => {
    users.value = users.value.filter(user => user.id !== userId)
}

const searchQuery = ref(null);

watch(searchQuery, debounce(() => {
    search();
}, 300));

const search = () => {
    axios.get('/api/users/search', {
        params: {
            query: searchQuery.value,
        }
    })
    .then(response => {
        users.value = response.data;
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
            <!-- <div class="row"> -->
                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" @click="addUser">
                            Create New User
                        </button>
                        <div>
                            <input v-model="searchQuery" type="text" class="form-control" placeholder="Search User">

                        </div>
                    </div>
                </div>
            <!-- </div> -->
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="users.length > 0">
                            <UserListItem v-for="(user, index) in users"
                                :key="user.id"
                                :user="user"
                                :index="index"
                                @user-edit="userEdit"
                                @user-deleted="userDeleted"
                            />
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center text-danger">
                                    No result found.
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
                    <h5 class="modal-title" id="userFormModelLabel">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add New User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{errors, formValues}" :initial-values="formValues">
                    <div class="modal-body">

                            <div class="form-group">
                                <label for="name" class="col-form-label">User Name</label>
                                <Field name="name" type="text" class="form-control" id="name" :class="{'is-invalid' : errors.name}" />
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
