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
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-plus-circle mr-1"></i>
                                Add New Appointment
                            </button>
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
</template>
<script setup>
import { ref, onMounted } from "vue";
import axios from 'axios';
import AppointmentListItems from './AppointmentListItems.vue';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';

const appointments = ref({data: []});
const getAppointments = (page = 1) => {
    axios.get(`/api/appointments?page=${page}`)
        .then(response => {
            appointments.value = response.data;
        });
}
onMounted(() => {
    getAppointments();
});
</script>
