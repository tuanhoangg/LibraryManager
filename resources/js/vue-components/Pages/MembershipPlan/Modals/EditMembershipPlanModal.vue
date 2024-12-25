<template lang="">
<div class="modal fade" id="edit-plan-modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm gói</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <Form @submit="updatePlan"  :validation-schema="schema">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tên gói</label>
                        <Field type="text" class="form-control" placeholder="Tên gói ..." name="name" v-model="currentPlan.name"/>
                        <ErrorMessage class="text-danger" name="name"/>

                    </div>
                    <div class="form-group">
                        <label>Thời hạn</label>
                        <Field type="number" class="form-control" placeholder="Thời hạn ..." name="frequency" v-model="currentPlan.frequency"/>
                        <ErrorMessage class="text-danger" name="frequency"/>

                    </div>
                    <div class="form-group">
                        <label>Giá</label>
                        <Field type="number" class="form-control" placeholder="Giá gói ..." name="price" v-model="currentPlan.price"/>
                        <ErrorMessage class="text-danger" name="price"/>

                    </div>
                    <div class="form-group">
                        <label>Số sách</label>
                        <Field type="number" class="form-control" placeholder="Số sách ..." name="limit_book" v-model="currentPlan.limit_book"/>
                        <ErrorMessage class="text-danger" name="limit_book"/>
                    </div>
                    <div class="form-group">
                        <label>Giảm giá trả muộn (%)</label>
                        <Field type="number" class="form-control" placeholder="% Giảm giá ..." name="late_fee_discount" v-model="currentPlan.late_fee_discount"/>
                        <ErrorMessage class="text-danger" name="late_fee_discount"/>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <Field v-slot="{ field, errors }" name="description"  v-model="currentPlan.description">
                            <textarea v-bind="field" class="form-control" rows="3" placeholder="Mô tả ..." name="description"></textarea>
                        </Field>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Trở lại</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </Form>
        </div>

    </div>

</div>
</template>
<script>

import axios from 'axios';
import { Field, Form, ErrorMessage, defineRule, useForm } from 'vee-validate';
const { setFieldValue, setValues } = useForm();
import * as Yup from 'yup';
const schema = Yup.object().shape({
    price: Yup.number()
        .typeError('Giá phải là số')
        .required('Vui lòng nhập giá'),
    name: Yup.string()
        .required('Vui lòng nhập tên'),
    frequency: Yup.number()
        .typeError('Thời hạn phải là số')
        .required('Vui lòng nhập thời hạn'),
    limit_book: Yup.number()
        .typeError('Số sách giới hạn phải là số')
        .required('Vui lòng nhập số sách giới hạn'),
    description: Yup.string()
        .required('Vui lòng nhập mô tả'),
    late_fee_discount: Yup.number()
        .typeError('% Giam phat phải là số')
        .required('Vui lòng nhập ti le giam phat'),
});

export default {
    props: {
        loadItems: Function,
        isEdit: Boolean,
        plan: Object,
        isModalOpen: Boolean
    },
    components: {
        Field,
        Form,
        ErrorMessage,
    },
    data() {
        return {
            form: {},
            currentPlan: {},
        }
    },
    methods: {
        async updatePlan(values, { resetForm }) {
            values.id = this.currentPlan.id;
            const response = await axios.post('/membership-plans/api/update-membership-plan', values);

            toastr[response.data.status](response.data.msg);

            if (response.data.status == 'success') {
                $('#edit-plan-modal').modal('hide');
                this.$emit('loadItems');
                resetForm();
            }
        },
        validateName(value) {
            if (!value) {
                return 'Trường tên không được bỏ trống';
            }
            return true;
        },
    },
    watch: {
        plan() {
            // if (this.isModalOpen === true) {
            this.currentPlan.price = this.plan.price;
            this.currentPlan.name = this.plan.name;
            this.currentPlan.frequency = this.plan.frequency;
            this.currentPlan.description = this.plan.description;
            this.currentPlan.limit_book = this.plan.limit_book;
            this.currentPlan.id = this.plan.id;
            this.currentPlan.late_fee_discount = this.plan.late_fee_discount;

            // }
        }

    },
}
</script>
<style lang="">

</style>