<template>
    <div class="details-markings-component py-4">
        <div class="dt-header">
            <div class="flex justify-between p-0">
                <div class="self-center flex-auto">
                    <h3 class="text-xl font-bold">
                        <img :src="viewPhoto(employee.avatar)" class="inline-block rounded-full w-12 h-12" alt="" v-if="employee.avatar">
                        <span class="inline-block">{{ employee.name_employee + ' ' + employee.surname_employee }} | {{employee.cod_marking}}</span>
                    </h3>
                    <h3 class="text-gray-500 text-sm">{{ employee.departament ? employee.departament.display_name : '' }}</h3>
                </div>
            </div>
        </div>
        <div class="dt-body mt-4 overflow-x-auto">
            <table class="table-auto w-full text-sm text-gray-800">
                <thead class="text-center border-b-2">
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Dia</th>
                    <th>Entrada</th>
                    <th>°C</th>
                    <th>Salida</th>
                    <th>°C</th>
                    <th>Horas Trabajadas</th>
                    <th>Horas extras</th>
                    <th>Llegadas tarde</th>
                    <th>Permiso</th>
                </thead>
                <tbody class="text-center">
                    <tr v-for="(marking, index) in markings.markings" :key="index" class="border-b-2">
                        <td>
                            <img :src="viewPhoto(marking.avatar)" class="inline-block rounded-full w-8 h-8 cursor-pointer" @mouseover="showPhoto = index" @mouseleave="showPhoto = ''" alt="" v-if="marking.avatar">
                            <div class="absolute w-40 h-40 p-2 border bg-white rounded z-10"  :class="showPhoto === index ? 'block' : 'hidden'"  >
                                <img :src="viewPhoto(marking.avatar)" class="cursor-pointer w-full h-full" alt="">
                            </div>
                        </td>
                        <td>{{ marking.date }}</td>
                        <td>{{ marking.day }}</td>
                        <td :class="[marking.in >= 'Sin marcación' ? 'text-red-500' : 'text-gray-600']">{{ marking.in }}</td>
                        <td :class="[marking.temp_in > 37.3 ? 'text-red-500 font-bold' : 'text-green-600']">{{ marking.temp_in }}</td>
                        <td :class="[marking.out >= 'Sin marcación' ? 'text-red-500' : 'text-gray-600']">{{ marking.out }}</td>
                        <td :class="[marking.temp_out > 37.3 ? 'text-red-500 font-bold' : 'text-green-600']">{{ marking.temp_out }}</td>
                        <td>{{ marking.hours_worked }}</td>
                        <td>{{ marking.extra_hours }}</td>
                        <td>{{ marking.late_arrivals }}</td>
                        <td :class="[marking.permission == 'AUSENTE' ? 'text-red-600' : marking.permission == 'SI' ? 'text-blue-600' : '']" v-if="marking.id_action != null">
                            <a :href="'/actions/'+marking.id_action" target="_blank">
                                {{ marking.permission }}
                            </a>
                        </td>
                        <td :class="[marking.permission == 'AUSENTE' ? 'text-red-600' : marking.permission == 'SI' ? 'text-blue-600' : '']" v-else>{{ marking.permission }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <th>TOTAL:</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ markings.total_hours_worked}}</th>
                    <th>{{ markings.total_extra_hours }}</th>
                    <th :class="[isArrivals(markings.total_late_arrivals) ? 'text-red-600' : '']">{{ markings.total_late_arrivals }}</th>
                    <th></th>
                </tfoot>
            </table>
            <div class="text-center font-bold text-xl">{{ employee.company.display_name }}</div>
        </div>
    </div>
</template>
<script>
export default {
    props:['employee','startdate','enddate'],
    data(){
        return{
            markings: [],
            showPhoto: '',
        }
    },
    methods: {
        fetchDataMarkings(){
            let url = '/apiassists/show/' + this.employee.id
            let vm = this
            axios.get(url,{
                params:{
                    start_date: vm.startdate,
                    end_date: vm.enddate
                }
            }).then(response => {
                this.markings = response.data
            })
        },
        isArrivals(data){
            var str = String(data);
            var res = str.replace(":", ".");
            return res > 0;
        },
        viewPhoto(photo)
        {
            return photo;
        }
    },
    mounted() {
        this.fetchDataMarkings()
    },
}
</script>
