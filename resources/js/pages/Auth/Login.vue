<script setup lang="ts">
import AuthBase from '@/layouts/AuthLayout.vue';
import {Field, FieldError, FieldGroup, FieldLabel, FieldSet,} from '@/components/ui/field';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {Button} from "@/components/ui/button";
import {Input} from "@/components/ui/input";
import {loginAction, register} from "@/routes";
import {Checkbox} from "@/components/ui/checkbox";

const form = useForm({
    username: '',
    password: '',
    remember: false,
})
</script>

<template>
    <AuthBase
        title="Войдите в свою учетную запись"
        description="Введите свой логин и пароль, чтобы войти в систему"
    >
        <Head title="Вход" />
        
        <form @submit.prevent="form.post(loginAction().url)">
            <FieldGroup>
                <FieldSet>
                    <FieldGroup>
                        <!-- Логин -->
                        <Field :data-invalid="!!form.errors.username">
                            <FieldLabel for="username">Логин</FieldLabel>
                            <Input v-model="form.username" :aria-invalid="!!form.errors.username" id="username" required />
                            <FieldError v-if="form.errors.username" :errors="[form.errors.username]" />
                        </Field>
                        <!-- Пароль -->
                        <Field :data-invalid="!!form.errors.password">
                            <FieldLabel for="password">Пароль</FieldLabel>
                            <Input v-model="form.password" :aria-invalid="!!form.errors.password" id="password" type="password" required />
                            <FieldError v-if="form.errors.password" :errors="[form.errors.password]" />
                        </Field>
                        <div class="flex items-center space-x-2">
                            <Checkbox id="remember" v-model="form.remember" />
                            <Label for="remember">Запомнить меня</Label>
                        </div>
                    </FieldGroup>
                </FieldSet>
                <Field>
                    <Button type="submit">
                        Войти
                    </Button>
                    <div class="text-center text-sm text-muted-foreground">
                        У вас нет учетной записи?
                        <Link :href="register().url" class="text-foreground underline decoration-zinc-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-zinc-500">Зарегистрироваться</Link>
                    </div>
                </Field>
            </FieldGroup>
        </form>
    </AuthBase>
</template>
