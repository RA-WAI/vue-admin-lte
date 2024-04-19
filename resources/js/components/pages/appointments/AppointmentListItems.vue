<template>
    <tr>
        <td scope="role">{{ index + 1 }}</td>
        <td>{{ appointment?.title }}</td>
        <td>{{ appointment?.client?.first_name }} {{ appointment?.client?.last_name }}</td>
        <td>{{ appointment?.start_time }}</td>
        <td>{{ appointment?.end_time }}</td>
        <td>
            <span class="badge" :class="`badge-${appointment?.status?.color}`">
                {{ appointment?.status?.name }}
            </span>
        </td>
        <td>
            <router-link :to="`/admin/appointments/${appointment.id}/edit`">
                <i class="fa fa-edit text-warning "></i>
            </router-link>


            <i @click="deleteData(appointment.id)" class="fa fa-trash text-danger ml-2"></i>

        </td>
    </tr>
</template>
<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { useToastr } from '../../../toastr';

const toastr = useToastr();
const props = defineProps({
    index: Number,
    appointment: Object,
});

const emit = defineEmits(['deleteAppointment']);

const deleteData = (id) => {
    Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/appointments/${id}`)
            .then((response) => {
                toastr.success(response.data.message);
                emit('deleteAppointment', id);
            })
            .catch((errors) => {
                if (errors.response.data.message) {
                    toastr.error(errors.response.data.message);
                }
            });
    }
    });

}
</script>
