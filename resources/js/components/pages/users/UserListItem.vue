<template>
    <tr>
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.formatted_created_at }}</td>
        <td>
            <select name="" id="" class="form-control" @change="chnageRole(user, $event.target.value)">
                <option v-for="role in roles" :value="role.value" :selected="user.role === role.name" >
                    {{ role.name }}
                </option>
            </select>
        </td>
        <td>
            <a @click.prevent="$emit('userEdit', user)">
                <i class="fa fa-edit text-warning "></i>
            </a>
            <a @click.prevent="confirmUserDelete(user)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>

        </td>
    </tr>

    <!-- Delete User Model -->
    <div class="modal fade" id="confirmDeleteModel" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModelLabel">
                        Delete User
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="model-body">
                    <p class="text-center p-3">
                        Are you sure to delete this user?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Cancel</button>
                    <button @click="deleteUser" type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

    import { ref } from 'vue';
    import { useToastr } from '../../../toastr.js';

    defineProps({
        user: Object,
        index: Number,
    });

    const userIdBeingDeleted = ref(null);

    const toastr  = useToastr();
    const editing = ref(false);

    const confirmUserDelete = (user) => {
        userIdBeingDeleted.value = user.id;
        $("#confirmDeleteModel").modal("show");
    }

    const emit = defineEmits(['userDeleted', 'userEdit']);

    const deleteUser = () => {
        axios.delete(`/api/users/${userIdBeingDeleted.value}`)
            .then(() => {
                $("#confirmDeleteModel").modal("hide");

                toastr.success('User deleted successfully.');

                emit('userDeleted', userIdBeingDeleted.value)
            });
    }

    const roles = ref([
        {
            name: 'ADMIN',
            value: 1
        },
        {
            name: 'USER',
            value: 2
        }
    ]);

    const chnageRole = (user, role) => {
        axios.patch(`/api/users/${user.id}/change-role`, {
            role: role,
        })
        .then(() => {
            toastr.success('Change role successfully.');
        })
    }

</script>
