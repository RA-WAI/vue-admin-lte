import Dashboard from "./components/Dashboard.vue";
import ListAppointments from "./components/pages/appointments/ListAppointments.vue";
import AppointmentForm from './components/pages/appointments/AppointmentForm.vue';
import UserList from "./components/pages/users/UserList.vue";
import UpdateSetting from "./components/pages/settings/UpdateSetting.vue";
import UpdateProfile from "./components/pages/profile/UpdateProfile.vue";
import Login from "./components/pages/auth/Login.vue";

export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },
    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: ListAppointments,
    },
    {
        path: '/admin/appointments/create',
        name: 'admin.appointments.create',
        component: AppointmentForm,
    },
    {
        path: '/admin/appointments/:id/edit',
        name: 'admin.appointments.edit',
        component: AppointmentForm,
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: UserList,
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSetting,
    },
    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
]
