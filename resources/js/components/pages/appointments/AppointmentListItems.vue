<template>
    <tr>
        <td scope="role">{{ index + 1 }}</td>
        <td>{{ appointment?.client?.first_name }} {{ appointment?.client?.last_name }}</td>
        <td>{{ appointment?.start_time }}</td>
        <td>{{ appointment?.end_time }}</td>
        <td>
            <span class="badge" :class="`badge-${appointment?.status?.color}`">
                {{ appointment?.status?.name }}
            </span>
        </td>
        <td></td>
    </tr>
</template>
<script setup>
const props = defineProps({
    index: Number,
    appointment: Object,
});
</script>
