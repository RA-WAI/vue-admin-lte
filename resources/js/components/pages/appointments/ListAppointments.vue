<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Appointment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">

                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <router-link to="/admin/appointments/create">
                                <button type="button" class="btn btn-primary">
                                    <i class="fa fa-plus-circle mr-1"></i>
                                    Add New Appointment
                                </button>
                            </router-link>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Client Name</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody v-if="appointments.data.length">
                            <AppointmentListItems
                                v-for="(appointment, index) in appointments.data"
                                :key="appointment.id"
                                :appointment="appointment"
                                :index="index"
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

            <Bootstrap4Pagination
                :data="appointments"
                @pagination-change-page="getAppointments"
            />
        </div>
    </div>

    <!-- Appointment Form Model -->
    <div class="modal fade" id="appointmentModel" tabindex="-1" role="dialog" aria-labelledby="appointmentModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModelLabel">
                        <!-- <span v-if="editing">Edit User</span>
                        <span v-else>Add New User</span> -->
                        <span >Add New Appointment</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form"
                    :initial-values="formValues"
                    @submit="handleSubmit"
                    v-slot="{errors}"
                >
                    <div class="modal-body">
                            <div class="form-group">
                                <label for="title" class="col-form-label">Title</label>
                                <Field name="title" type="title" id="title" class="form-control"
                                :class="{'is-invalid' : errors.title}"
                                />
                                <ErrorMessage name="title" class="text-danger" />
                            </div>
                            <div class="form-group">
                                <label for="client_id" class="col-form-label">Client</label>
                                <Field name="client_id" as="select"
                                    class="form-control" id="client_id"
                                    :class="{'is-invalid' : errors.client_id}"
                                    >
                                    <option value="">Select Client</option>
                                    <option v-for="client in clients" :value="client.id">
                                        {{ client.first_name }}
                                    </option>
                                </Field>
                                <ErrorMessage name="client_id" class="text-danger" />
                            </div>
                            <div class="form-group">
                                <label for="start_time" class="col-form-label">Start Time</label>
                                <Field name="start_time" type="start_time" id="start_time" class="form-control"
                                :class="{'is-invalid' : errors.start_time}"
                                />
                                <ErrorMessage name="start_time" class="text-danger" />
                            </div>
                            <div class="form-group">
                                <label for="end_time" class="col-form-label">End Time</label>
                                <Field name="end_time" type="end_time" id="end_time" class="form-control"
                                :class="{'is-invalid' : errors.end_time}"

                                />
                                <ErrorMessage name="end_time" class="text-danger" />
                            </div>
                            <div class="form-group">
                                <label for="status" class="col-form-label">Status</label>
                                <Field name='status' class='form-control' as="select"
                                    :class="{'is-invalid' : errors.status}"
                                >
                                    <option value="">Select Status</option>
                                    <option value="1">SCHEDULED</option>
                                    <option value="2">CONFIRMED</option>
                                    <option value="3">CANCELLED</option>
                                </Field>
                                <ErrorMessage name="status" class="text-danger" />
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-form-label">Description</label>
                                <Field name="description" type="description" id="description" class="form-control"
                                :class="{'is-invalid' : errors.description}"
                                as="textarea"
                                />
                                <ErrorMessage name="description" class="text-danger" />
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
<script setup>
import { ref, onMounted } from "vue";
import axios from 'axios';
import AppointmentListItems from './AppointmentListItems.vue';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import { Form, Field, ErrorMessage } from 'vee-validate';

const appointments = ref({data: []});
const clients = ref({data: []});
const getAppointments = (page = 1) => {
    axios.get(`/api/appointments?page=${page}`)
        .then(response => {
            appointments.value = response.data;
        });
}

const getAllClients = () => {
    axios.get('/api/all-clients')
        .then(response => {
            clients.value = response.data;
        })
        .catch(error => {
            console.log(error);
        })
}

const formValues = {};

const addAppointment = () => {
    $('#appointmentModel').modal('show');
}

const handleSubmit = (values, actions) => {
    createAppointment(values, actions);
}

const createAppointment = (values, {resetForm, setErrors}) =>{
    axios.post('/api/appointments', values)
        .then((response) => {
            console.log(response);
        })
        .catch((errors) => {
            if (errors.response.data.errors) {
                setErrors(errors.response.data.errors);
            }
        });
}

onMounted(() => {
    getAppointments();
    getAllClients();
});
</script>
