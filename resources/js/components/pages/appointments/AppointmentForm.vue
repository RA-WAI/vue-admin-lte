<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointment Create</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments">Appointments</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            Create
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <Form ref="form"
                    :initial-values="formValues"
                    @submit="handleSubmit"
                    v-slot="{errors}"
                    >
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="title" class="col-form-label">Title</label>
                                <Field name="title" type="title" id="title" class="form-control"
                                :class="{'is-invalid' : errors.title}"
                                />
                                <ErrorMessage name="title" class="invalid-feedback" />
                            </div>
                        </div>
                        <div class="col-6">
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
                            <ErrorMessage name="client_id" class="invalid-feedback" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="start_time" class="col-form-label">Start Time</label>
                            <Field name="start_time" v-slot="{field}">
                                <input type="datetime-local"
                                    v-bind="field"
                                    id="start_time"
                                    class="form-control"
                                    :class="{'is-invalid' : errors.start_time}"
                                >
                            </Field>
                            <ErrorMessage name="start_time" class="invalid-feedback" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="end_time" class="col-form-label">End Time</label>
                            <Field name="end_time" v-slot="{field}">
                                <input type="datetime-local"
                                    v-bind="field"
                                    id="end_time"
                                    class="form-control"
                                    :class="{'is-invalid' : errors.end_time}"
                                >
                            </Field>
                            <ErrorMessage name="end_time" class="invalid-feedback" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
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
                        <ErrorMessage name="status" class="invalid-feedback" />
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="description" class="col-form-label">Description</label>
                        <Field name="description" type="description" id="description" class="form-control"
                        :class="{'is-invalid' : errors.description}"
                        as="textarea"
                        />
                        <ErrorMessage name="description" class="invalid-feedback" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right">
                    <router-link to="/admin/appointments" class="btn btn-secondary mr-2">
                        Cancel
                    </router-link>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </Form>
    </div>
</div>
</div>
</div>
</template>
<script setup>
import axios from 'axios';
import { Form, Field, ErrorMessage } from 'vee-validate';
import { ref, onMounted } from "vue";
import { useToastr } from '../../../toastr.js';
import { useRouter } from 'vue-router';

const toastr  = useToastr();
const formValues = {};
const clients = ref({data: []});
const router = useRouter()

const handleSubmit = (values, actions) => {
    createAppointments(values, actions);
};

const createAppointments = (values, {resetForm, setErrors}) => {
    axios.post('/api/appointments', values)
        .then((response) => {
            resetForm();
            toastr.success(response.data.message);
            router.push('/admin/appointments');
        })
        .catch((errors) => {
            if (errors.response.data.errors) {
                setErrors(errors.response.data.errors);
            } else {
                toastr.error(errors.response.data.message);
            }

        });
}

const getAllClients = () => {
    axios.get('/api/all-clients')
    .then(response => {
        clients.value = response.data;
    });
};

onMounted(() => {
    getAllClients();
});

</script>
