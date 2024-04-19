<script setup>
import axios from 'axios';
import { Form, Field, ErrorMessage } from 'vee-validate';
import { ref, onMounted, reactive } from "vue";
import { useToastr } from '../../../toastr.js';
import { useRouter, useRoute } from 'vue-router';
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/light.css';

const form = reactive({
    'title': '',
    'client_id': '',
    'start_time': '',
    'end_time': '',
    'description': '',
    'status': '',
});
const toastr  = useToastr();
const clients = ref({data: []});
const router = useRouter();
const route = useRoute();

const handleSubmit = (values, actions) => {
    if (editing.value) {
        editAppointment(values, actions);
    } else {
        createAppointments(values, actions);
    }
};

const editAppointment = (values, actions) => {
    axios.put(`/api/appointments/${route.params.id}`, values)
        .then((response) => {
            toastr.success(response.data.message);
            router.push('/admin/appointments');
        })
        .catch((errors) => {
            if (errors.response.data.errors) {
                actions.setErrors(errors.response.data.errors);
            } else {
                toastr.error(errors.response.data.message);
            }
        });
}

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

const editing = ref(false);

const getAppointment = () => {
    axios.get(`/api/appointments/${route.params.id}/edit`)
        .then(({data}) => {
            form.title = data.title;
            form.client_id = data.client_id;
            form.start_time = data.formatted_start_time;
            form.end_time = data.formatted_end_time;
            form.description = data.description;
            form.status = data.status;
        });
}

onMounted(() => {

    if (route.name === 'admin.appointments.edit') {
        editing.value = true;

        getAppointment();
    }

    getAllClients();
    flatpickr('.flatpickr', {
        enableTime: true,
        dateFormat: 'd-m-Y h:i K',
        // minDate: 'today',
        defaultHour: 9,
    });
});

</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointment {{ editing ? 'Edit' : 'Create' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments">Appointments</router-link>
                        </li>
                        <li v-if="editing" class="breadcrumb-item active">
                            Edit
                        </li>
                        <li v-else class="breadcrumb-item active">
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
                    <Form
                    @submit="handleSubmit"
                    v-slot="{errors}"
                    >
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="title" class="col-form-label">Title</label>
                                <Field name="title" type="text" id="title" class="form-control"
                                :class="{'is-invalid' : errors.title}"
                                v-model="form.title"
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
                                v-model="form.client_id"
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
                            <Field name="start_time" v-slot="{field}" v-model="form.start_time">
                                <input type="datetime-local"
                                    v-bind="field"
                                    id="start_time"
                                    class="form-control flatpickr"
                                    :class="{'is-invalid' : errors.start_time}"
                                >
                            </Field>
                            <ErrorMessage name="start_time" class="invalid-feedback" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="end_time" class="col-form-label">End Time</label>
                            <Field name="end_time" v-slot="{field}" v-model="form.end_time">
                                <input type="text"
                                    v-bind="field"
                                    id="end_time"
                                    class="form-control flatpickr"
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
                            v-model="form.status"
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
                        v-model="form.description"
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
                    <button type="submit" class="btn btn-primary">{{ editing ? 'Update' : 'Create' }}</button>
                </div>
            </div>
        </Form>
    </div>
</div>
</div>
</div>
</template>

