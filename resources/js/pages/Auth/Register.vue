<script setup lang="ts">
import AuthBase from '@/layouts/AuthLayout.vue';
import {Field, FieldError, FieldGroup, FieldLabel, FieldSet,} from '@/components/ui/field';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {Button} from "@/components/ui/button";
import {Input} from "@/components/ui/input";

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
})
</script>

<template>
    <AuthBase
        title="Создайте учетную запись"
        description="Введите свои данные, чтобы зарегистрироваться"
    >
        <Head title="Регистрация" />
        
        <form action="">
            <FieldGroup>
                <FieldSet>
                    <FieldGroup>
                        <!-- ФИО -->
                        <Field :data-invalid="!!form.errors.name">
                            <FieldLabel for="name">ФИО</FieldLabel>
                            <Input v-model="form.name" :aria-invalid="!!form.errors.name" id="name" required />
                            <FieldError v-if="form.errors.name" :errors="[form.errors.name]" />
                        </Field>
                        <!-- Логин -->
                        <Field :data-invalid="!!form.errors.username">
                            <FieldLabel for="username">Логин</FieldLabel>
                            <Input v-model="form.username" :aria-invalid="!!form.errors.username" id="username" required />
                            <FieldError v-if="form.errors.username" :errors="[form.errors.username]" />
                        </Field>
                        <!-- Email -->
                        <Field :data-invalid="!!form.errors.email">
                            <FieldLabel for="email">Email</FieldLabel>
                            <Input v-model="form.email" :aria-invalid="!!form.errors.email" id="email" type="email" required />
                            <FieldError v-if="form.errors.email" :errors="[form.errors.email]" />
                        </Field>
                        <!-- Пароль -->
                        <Field :data-invalid="!!form.errors.password">
                            <FieldLabel for="password">Пароль</FieldLabel>
                            <Input v-model="form.password" :aria-invalid="!!form.errors.password" id="password" type="password" required />
                            <FieldError v-if="form.errors.password" :errors="[form.errors.password]" />
                        </Field>
                        <!-- Повторный ввод пароля -->
                        <Field :data-invalid="!!form.errors.password">
                            <FieldLabel for="password">Повторите пароль</FieldLabel>
                            <Input v-model="form.password" :aria-invalid="!!form.errors.password" id="password" type="password" required />
                            <FieldError v-if="form.errors.password" :errors="[form.errors.password]" />
                        </Field>
                    </FieldGroup>
                </FieldSet>
                <Field>
                    <Button type="submit">
                        Сохранить
                    </Button>
                    <div class="text-center text-sm text-muted-foreground">
                        У вас нет учетной записи?
                        <Link class="text-foreground underline decoration-zinc-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-zinc-500">Зарегистрироваться</Link>
                    </div>
                </Field>
            </FieldGroup>
        </form>
    </AuthBase>
</template>
