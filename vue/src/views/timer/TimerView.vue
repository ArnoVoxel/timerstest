<template>
<div>
    <h1 @click="getPage">
        Ça va timer !
    </h1>

    <div>
        <b-container fluid>
            
                <b-form @submit.prevent="onSubmit" @reset="onReset">
                    <b-row>
                        <b-col cols="12" md="4">
                            <multiselect id="category" class="mt-3" v-model="form.category" label="label" :options="options.categories" placeholder="choisissez une catégorie" ></multiselect>
                            <p v-if="errors != null">{{ errors.category}}</p>
                            <p v-else></p>
                        </b-col>
                        <b-col cols="12" md="4">
                            <multiselect id="company" class="mt-3" v-model="form.company" label="label" :options="options.companies" placeholder="choisissez une société" ></multiselect>
                            <p v-if="errors != null">{{ errors.company}}</p>
                            <p v-else></p>
                        </b-col>
                        <b-col cols="12" md="4">
                            <b-button class="mt-3 col-12 col-md-6" type="submit" variant="primary">Start Now</b-button>
                            <b-button @click="stopTimer " class="mt-3 col-12 col-md-6" type="button" variant="danger">Stop</b-button>
                        </b-col>
                    </b-row>
                </b-form>

        </b-container>
    </div>

    <div>
        <div class="overflow-auto">

            <!-- dropdown menu to filter the content of the table -->
            <b-button class="mt-3" v-b-toggle.collapse-1 variant="primary">filters</b-button>
            <b-collapse id="collapse-1">
                <b-row class="mt-3">
                    <b-col>
                        <b-form @submit.prevent="getPagination">
                            <b-row>
                                <b-col>
                                    <b-form-checkbox-group  v-model="searchFilter.category" value-field="id" text-field="label" :options="options.categories"></b-form-checkbox-group>
                                </b-col>
                                <b-col>
                                    <b-input-group>
                                        <b-form-input v-model="searchFilter.company" placeholder="search company name" type="text"></b-form-input>
                                    </b-input-group>
                                </b-col>
                            </b-row>
                            <b-row class="mt-3">
                                <b-col>
                                    <p>from :</p>
                                    <b-input v-model="searchFilter.from" type="date"></b-input>
                                </b-col>
                                <b-col>
                                    <p>to :</p>
                                    <b-input v-model="searchFilter.to" type="date"></b-input>
                                </b-col>
                            </b-row>
                    <b-button class="mt-3 col-12 col-md-6" type="submit" variant="primary">Search</b-button>
                    <b-button class="mt-3 col-12 col-md-6" type="button" variant="warning" @click="getReset">Reset</b-button>
                        </b-form>
                    </b-col>
                </b-row>
            </b-collapse>

            <b-alert v-model="showDismissibleAlert" variant="danger" dismissible>
                Suppression du timer {{ infoDelete.id }} ?
                <b-button variant="danger" type="submit" @click.prevent="onDelete(infoDelete)">Confirmer</b-button>
            </b-alert>

            <b-form >
                <b-table
                class="mt-3"
                id="myTable"
                ref="myTable"
                striped bordered hover 
                :items="timers" 
                :fields="fields"
                >
                    <template #cell(started_at)="data">
                        <b-input-group v-if="data.item.isEdit">
                        <b-row>
                            <b-col cols="12">
                                <b-form-datepicker  v-model="formUpdate.started_at"></b-form-datepicker>
                            </b-col>
                            <b-col cols="12">
                                <b-form-timepicker v-model="timePick.time" size="sm" ></b-form-timepicker>
                            </b-col>
                            <p class="text-danger" v-if="errors != null">{{ errors.started_at}}</p>
                            <p v-else></p>
                        </b-row>
                        </b-input-group>
                        <span v-else>{{data.value}}</span>
                    </template>

                    <template #cell(ended_at)="data">
                        <b-input-group v-if="data.item.isEdit">
                            <b-row>
                                <b-col cols="12">
                                    <b-form-datepicker  v-model="formUpdate.ended_at"></b-form-datepicker>
                                </b-col>
                                <b-col cols="12">
                                    <b-form-timepicker v-model="timePick.timeEnd" size="sm" ></b-form-timepicker>
                                </b-col>
                                <p class="text-danger" v-if="errors != null">{{ errors.ended_at}}</p>
                                <p v-else></p>
                            </b-row>
                        </b-input-group>
                        <span v-else>{{data.value}}</span>
                    </template>

                    <template #cell(time_spent)="data" >
                        <b-input-group v-if="data.item.isEdit">
                            <b-row>
                                <b-col cols="12">
                                    <b-form-timepicker v-model="formUpdate.time_spent" size="sm" ></b-form-timepicker>
                                </b-col>
                            </b-row>
                        </b-input-group>
                        <span v-else>{{data.value}}</span>
                    </template>

                    <template #cell(company_label)="data">
                        <multiselect v-if="data.item.isEdit" v-model="formUpdate.company" label="label" :options="options.companies" ></multiselect>
                        <span v-else>{{data.value}}</span>
                    </template>

                    <template #cell(category_label)="data">
                        <multiselect v-if="data.item.isEdit" v-model="formUpdate.category" label="label" :options="options.categories" placeholder="choisissez une catégorie" ></multiselect>
                        <span v-else>{{data.value}}</span>
                    </template>

                    <template #cell(modify)="data" >
                        <b-button v-if="!data.item.isEdit" variant="warning" @click="editRowHandler(data)">Edit</b-button>
                        <b-row v-else class="d-flew flex-row">
                            <b-input-group v-if="data.item.isEdit" align-self="center">
                                <b-button  variant="warning" type="submit" @click.prevent="onUpdate(data)">Done</b-button >
                                <b-button  variant="warning" type="submit" @click.prevent="data.item.isEdit=false">Cancel</b-button >
                            </b-input-group>
                        </b-row>
                    </template>

                    <template #cell(delete)="data">
                        <b-button variant="danger" type="submit" @click.prevent="onAlertDelete(data)">X</b-button>
                    </template>

                </b-table>

                    <b-pagination
                        class="mb-5"
                        v-model="pagination.currentPage"
                        :total-rows="pagination.nbInvoices"
                        :per-page="pagination.perPage"
                        align="center"
                        @input="getPagination()"
                    />
                        
            </b-form>
        </div>
    </div>

</div>
</template>

<script>
import Multiselect from 'vue-multiselect';

    export default {name: 'TimerView',
                    components:{ Multiselect, },
                    data(){
                        return{
                            errors: {
                                'category': null,
                                'company': null,
                                'started_at': null,
                                'ended_at': null,
                                'time_spent': null,
                            },
                            timePick: [],
                            infoDelete: {
                                'id': ''
                            },
                            showDismissibleAlert: false,
                            keyword: '',
                            timers: [],
                            options :{
                                categories: [],
                                companies: [],
                            },
                            form :{
                                category : null,
                                company : null,
                            },
                            formUpdate:{
                                user_id: null,
                                started_at: null,
                                ended_at: null,
                                time_spent: null,
                                category : null,
                                company : null,
                            },
                            searchFilter:{
                                page: 1,
                                category: [],
                                company: null,
                                from: "",
                                to: "",
                            },
                            data:{},
                            pagination: {
                                currentPage: 1,
                                nbInvoices: 0,
                                perPage: 3,
                            },
                            fields:[
                                {
                                    key: 'id',
                                    label: 'Timer Id',
                                    type: 'integer',
                                    editable: false,
                                },
                                {
                                    key: 'started_at',
                                    label: 'Started At',
                                    type: 'date',
                                    editable: true
                                },
                                {
                                    key: 'ended_at',
                                    label: 'Ended At',
                                    type: 'date',
                                    editable: true
                                },
                                {
                                    key: 'time_spent',
                                    label: 'Time Spent',
                                    type: 'date',
                                    editable: true
                                },
                                {
                                    key: 'company_label',
                                    label: 'Company Name',
                                    type: 'select',
                                    editable: true,
                                    company_label:[]
                                },
                                {
                                    key: 'category_label',
                                    label: 'Category Name',
                                    type: 'select',
                                    editable: true,
                                    category_label:[]
                                },
                                {
                                    key: 'modify',
                                    label: 'Modify',
                                },
                                {
                                    key: 'delete',
                                    label: 'Delete',
                                    editable: false,
                                }
                            ],
                        }
                    },
                    computed: {
                        rows() {
                            return this.timers.length;
                        },
                        filtered(){
                            // return this.keyword ? 
                            // this.timers.filter(criteria =>
                            //     criteria.company_label.toLowerCase().includes(this.keyword.toLowerCase()) ||
                            //     criteria.category_label.toLowerCase().includes(this.keyword.toLowerCase()))
                            // : this.timers
                            return this.timers;
                        },
                    },
                    methods: {
                        getPagination(){
                            //let url = "http://127.0.0.1:8000/api/timers?page=" + this.pagination.currentPage;

                            if(this.searchFilter.company == null){
                                this.searchFilter.company = '';
                            }
                            this.searchFilter.page = this.pagination.currentPage;
                            console.log(this.searchFilter);

                            this.$axios
                                .get("http://127.0.0.1:8000/api/timers?page=" + this.pagination.currentPage + "&company=" + this.searchFilter.company + "&category=" + this.searchFilter.category + "&from=" + this.searchFilter.from + "&to=" + this.searchFilter.to)
                                .then((response) => {
                                    this.timers = response.data.data;
                                    this.pagination.nbInvoices = response.data.total;
                                    this.pagination.perPage = response.data.per_page;
                                })
                                .catch((error) => console.log(error));
                        },
                        getReset(){
                            this.searchFilter = {
                                page: 1,
                                category: [],
                                company: null,
                                from: "",
                                to: "",
                            }
                            this.getPagination();
                        },
                        // change the value on edit button to toggle editable inputs
                        editRowHandler(data){
                            // reverse the value of boolean for timers.isEdit on specific row
                            data.isEdit = !this.timers[data.index].isEdit;
                            this.$set(this.timers[data.index], 'isEdit', data.isEdit);

                            // preselected values from the selected row in the table
                            this.formUpdate.category = {id:data.item.category_id,
                                                        label:data.item.category_label};
                            this.formUpdate.company = {id:data.item.company_id,
                                                        label:data.item.company_label};
                            this.formUpdate.started_at = data.item.started_at;
                            this.formUpdate.ended_at = data.item.ended_at;
                            this.formUpdate.time_spent = data.item.time_spent;

                            this.timePick.time = data.item.started_at.split(' ').splice(1, 1).toString();
                            this.timePick.timeEnd = data.item.ended_at.split(' ').splice(1, 1).toString();
                        },
                        getItems(){
                            this.$axios.get('http://127.0.0.1:8000/api/timers')
                                .then((res)=>{
                                    this.timers = res.data.data;
                                    this.data = res.data;
                                    
                                    for(let i = 0; i < res.data.data.length; i++){
                                        this.timePick[i] = {'time' : res.data.data[i]['started_at'].split(' ').splice(1, 1).toString()};
                                        if(res.data.data[i]['ended_at'] != null){
                                            this.timePick[i] = {'timeEnd' : res.data.data[i]['ended_at'].split(' ').splice(1, 1).toString()};
                                        }
                                    }
                                });
                        },
                        getCategories(){
                            this.$axios.get('http://127.0.0.1:8000/api/categories')
                                .then((res)=>{
                                    this.options.categories = res.data.data;
                                    this.fields.categories_label = res.data.data.label;
                                    console.log(this.searchFilter);
                                });
                        },
                        getCompanies(){
                            this.$axios.get('http://127.0.0.1:8000/api/companies')
                                .then((res)=>{
                                    this.options.companies = res.data.data;
                                    this.fields.companies_id = res.data.data.id;
                                });

                        },
                        stopTimer(){
                            this.$axios.get('http://127.0.0.1:8000/api/timers/stop-timer')
                                .then(() => {
                                    this.getItems();
                                });
                        },
                        onSubmit() {
                            console.log(this.form);

                                this.$axios.post('http://127.0.0.1:8000/api/timers', this.form)
                                    .then(() => {
                                        this.getItems();
                                        this.form = {
                                            category : null,
                                            company : null,
                                        };
                                        this.errors = {
                                            'category' : null,
                                            'company' : null,
                                            'ended_at' : null,
                                            'time_spent' : null,
                                        };
                                    })
                                    .catch((error) => {console.log(error.response.data.errors);
                                                        this.errors = error.response.data.errors});
                        },
                        onReset(event) {
                            event.preventDefault()
                            // Reset our options values
                            this.categories = ''
                            this.companies = ''
                        },
                        onUpdate(data){

                            this.started_at_date = this.formUpdate.started_at.split(' ').splice(0, 1).toString()+" "+this.timePick.time;
                            this.ended_at_date = this.formUpdate.ended_at.split(' ').splice(0, 1).toString()+" "+this.timePick.timeEnd;

                            this.formUpdate = {
                                'user_id' : data.item.user_id,
                                'started_at' : this.started_at_date,
                                'ended_at' : this.ended_at_date,
                                'time_spent': this.formUpdate.time_spent,
                                'category' : this.formUpdate.category.id,
                                'company' : this.formUpdate.company.id,
                            };

                            console.log(this.formUpdate);

                            this.$axios.put('http://127.0.0.1:8000/api/timers/update/'+data.item.id, this.formUpdate)
                                .then(() => {
                                    this.getItems();
                                    this.formUpdate={
                                        user_id: null,
                                        started_at: null,
                                        ended_at: null,
                                        category : null,
                                        company : null,
                                    };
                                    this.errors = {
                                            'category' : null,
                                            'company' : null,
                                            'ended_at' : null,
                                            'time_spent' : null,
                                        };
                                })
                                .catch((error) => {console.log(error.response.data.errors);
                                                        this.errors = error.response.data.errors});
                            
                            data.item.company_name = this.timers[data.index].company_name;
                        },
                        onDelete(data){
                            this.$axios.delete('http://127.0.0.1:8000/api/timers/delete/'+data.id+'/'+data.user_id)
                                .then(() => {
                                    this.getItems();
                                    this.showDismissibleAlert = !this.showDismissibleAlert;
                                })
                                .catch((error) => console.log(error));
                        },
                        onAlertDelete(data){
                            this.infoDelete = data.item;
                            this.showDismissibleAlert = !this.showDismissibleAlert;
                        },
                        getPage(){
                            console.log(this.keyword);
                            console.log(this.searchFilter);

                            // console.log('this.data : ');
                            // console.log(this.data.data);
                            // console.log(JSON.stringify(this.data.data));
    
                            // console.log('this.timers : ');
                            // console.log(this.timers);
                            // console.log(JSON.stringify(this.timers));

                            // console.log('this.errors : ');
                            // console.log(this.errors);

                            // console.log('this.timePick : ');
                            // console.log(this.timePick);
                            // console.log(JSON.stringify(this.timePick));
                        },
                    },
                    mounted(){
                        //this.timers = this.timers.map(timer => ({...timer, isEdit: false}));
                        this.getPagination();
                        //this.getItems();
                        },
                    created(){
                        this.getItems();
                        //this.timers = this.timers.map(timer => ({...timer, isEdit: false}));
                        this.getCategories();
                        this.getCompanies();
                        },
                    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>