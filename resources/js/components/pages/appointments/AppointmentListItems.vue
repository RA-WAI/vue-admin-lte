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
            <a >
                <i class="fa fa-edit text-warning "></i>
            </a>
            <a >
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>
</template>
<script setup>
const props = defineProps({
    index: Number,
    appointment: Object,
});
</script>
