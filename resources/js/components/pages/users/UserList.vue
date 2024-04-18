<script setup>
import axios from 'axios';
import { onMounted, ref, reactive, watch } from 'vue';
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import { useToastr } from '../../../toastr.js';
import UserListItem from "./UserListItem.vue";
import { debounce } from "lodash";
import { Bootstrap4Pagination } from 'laravel-vue-pagination';

const users = ref({data: []});
const editing = ref(false);
const formValues = ref();
const form = ref(null);
const toastr  = useToastr();

const getUser = (page  = 1) => {
    axios.get(`/api/users?page=${page}`)
    .then( (response) => {
        users.value = response.data;
        selectAllUsers.value = [];
        selectAll.value = false;

    })
    .catch(error => { console.log(error) });
}

const createUser = (values, { resetForm, setErrors }) => {
    axios.post('/api/users', values)
        .then((response) => {
            users.value.data.unshift(response.data);
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
            const index = users.value.data.findIndex( user => user.id === response.data.id );
            users.value.data[index] = response.data;
            $('#userFormModel').modal('hide');
            toastr.success("User updated successfully")
        }).catch((error) => {
            console.log(error);
            if (error?.response?.data?.errors) {
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
    users.value.data = users.value.data.filter(user => user.id !== userId)
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
const  selectedUsers  = ref([]);
const toggleSelection = (user) => {
    const index = selectedUsers.value.indexOf(user.id);
    if(index  === -1) {
        selectedUsers.value.push(user.id);
    }else {
        selectedUsers.value.splice(index, 1);
    }
    if(selectedUsers.value.length < 1) {
        selectAll.value = false;
    }
};

const bulkDelete = () => {
    axios.delete('/api/users', {
        data: {
            ids: selectedUsers.value
        }
    })
    .then((response) => {
        users.value.data = users.value.data.filter(user => !selectedUsers.value.includes(user.id));
        selectedUsers.value = [];
        selectAll.value = false;
        toastr.success(response.data.message);
        getUser();
    });
}
const selectAll = ref(null);
const selectAllUsers = () => {
    if (selectAll.value) {
        // selectedUsers.value = users.value.data.map(user => user.id);
        users.value.data.map((user) => {
            selectedUsers.value.push(user.id);
        });
    } else {
        selectedUsers.value = [];
    }
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
                        <div class="d-flex">
                            <button type="button" class="btn btn-primary" @click="addUser">
                                <i class="fa fa-plus-circle mr-1"></i>
                                Add New User
                            </button>
                            <div v-if="selectedUsers.length > 0">
                                <button  type="button" class="ml-2 btn btn-danger" @click="bulkDelete" >
                                    <i class="fa fa-trash mr-1"></i>
                                    Delete Selected
                                </button>
                                <span class="ml-2">Selected {{ selectedUsers.length }} users</span>
                            </div>
                        </div>
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
                                <th>
                                    <input type="checkbox" v-model="selectAll" @change="selectAllUsers" />
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="users.data.length > 0">
                            <UserListItem v-for="(user, index) in users.data"
                                :key="user.id"
                                :user="user"
                                :index="index"
                                @user-edit="userEdit"
                                @user-deleted="userDeleted"
                                @toggle-selection="toggleSelection"
                                :select-all="selectAll"
                                :selected-users="selectedUsers"
                            />
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="7" class="text-center text-danger">
                                    No result found.
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <Bootstrap4Pagination
                :data="users"
                @pagination-change-page="getUser"
            />
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
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{errors}" :initial-values="formValues">
                    <div class="modal-body">

                            <div class="form-group">
                                <label for="name" class="col-form-label">User Name</label>
                                <Field name="name" type="text" class="form-control" id="name" :class="{'is-invalid' : errors.name}" />
                                <ErrorMessage class="invalid-feedback" name="name" />
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email</label>
                                <Field name="email" type="email" id="email" class="form-control" :class="{'is-invalid' : errors.email}" />
                                <ErrorMessage class="invalid-feedback" name="email" />
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-form-label">Password</label>
                                <Field type="password" name="password" id="password" class="form-control" :class="{'is-invalid' : errors.password}" />
                                <ErrorMessage class="invalid-feedback" name="password" />
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
