<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger
} from "@/components/ui/alert-dialog";
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import {Pen, Trash} from 'lucide-vue-next';
import {Head, Link, router} from '@inertiajs/vue3';
import {ticketsCreate, ticketsDestroy, ticketsEdit} from "@/routes";
import {Button} from "@/components/ui/button";
import {Badge} from "@/components/ui/badge";
import {HoverCard, HoverCardContent, HoverCardTrigger} from "@/components/ui/hover-card";

interface Ticket {
    id: number;
    title: string;
    reason: string;
    location: string;
    status: 'new' | 'completed' | 'closed';
    resolution_notes?: string;
    created_at: string;
}

const {tickets} = defineProps<{
    tickets: Ticket[];
}>();

const statusLabels: Record<string, string> = {
    'new': 'Новая',
    'completed': 'Выполнена',
    'closed': 'Закрыта'
};
</script>

<template>
    <Head title="Мои заявки" />
    
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            
            <!-- Если нет заявок -->
            <div v-if="tickets.length === 0" class="flex items-center justify-center flex-1">
                <p>Список заявок пуст.
                    <Link :href="ticketsCreate().url" class="underline">Добавить</Link>
                </p>
            </div>
            
            <div class="flex items-center justify-between gap-8 py-4">
                <div class="max-w-2xl">
                    <h4 class="font-medium">Список заявок</h4>
                    
                    <p class="mt-1 text-sm text-muted-foreground leading-relaxed">
                        Здесь отображаются заявки, которые вы создали.
                    </p>
                </div>
                
                <Button @click="router.get(ticketsCreate().url)" class="shrink-0">Оставить заявку</Button>
            </div>
            
            <!-- Таблица -->
            <div class="rounded-md border">
                <Table class="w-full table-fixed">
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[50px]">ID</TableHead>
                            <TableHead class="w-[300px]">Причина обращения</TableHead>
                            <TableHead class="w-[300px]">Примечания</TableHead>
                            <TableHead class="w-[200px]">Аудитория</TableHead>
                            <TableHead class="w-[100px]">Статус</TableHead>
                            <TableHead class="w-[120px] text-right">Действия</TableHead>
                        </TableRow>
                    </TableHeader>
                    
                    <TableBody>
                        <TableRow v-for="ticket in tickets" :key="ticket.id">
                            <TableCell class="align-middle">
                                {{ ticket.id }}
                            </TableCell>
                            
                            <!-- Причина обращения -->
                            <TableCell class="align-middle">
                                <HoverCard v-if="ticket.reason.length >= 50">
                                    <HoverCardTrigger as-child>
                                        <div class="truncate cursor-pointer">
                                            {{ ticket.reason }}
                                        </div>
                                    </HoverCardTrigger>
                                    
                                    <HoverCardContent class="w-96">
                                        <p class="text-sm whitespace-normal break-words">
                                            {{ ticket.reason }}
                                        </p>
                                    </HoverCardContent>
                                </HoverCard>
                                
                                <div v-else class="truncate">
                                    {{ ticket.reason }}
                                </div>
                            </TableCell>
                            
                            
                            <!-- Примечания -->
                            <TableCell class="align-middle">
                                <HoverCard v-if="ticket.resolution_notes && ticket.resolution_notes.length >= 50">
                                    <HoverCardTrigger as-child>
                                        <div class="truncate cursor-pointer">
                                            {{ ticket.resolution_notes }}
                                        </div>
                                    </HoverCardTrigger>
                                    
                                    <HoverCardContent class="w-96">
                                        <p class="text-sm whitespace-normal break-words">
                                            {{ ticket.resolution_notes }}
                                        </p>
                                    </HoverCardContent>
                                </HoverCard>
                                
                                <div v-else class="truncate">
                                    {{ ticket.resolution_notes ?? '–' }}
                                </div>
                            </TableCell>
                            
                            <TableCell class="align-middle">
                                {{ ticket.location }}
                            </TableCell>
                            
                            <TableCell class="align-middle">
                                <Badge
                                    :variant="ticket.status === 'new' ? 'outline' : 'default'"
                                >
                                    {{ statusLabels[ticket.status] }}
                                </Badge>
                            </TableCell>
                            
                            <TableCell class="align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Button
                                        @click="router.get(ticketsEdit(ticket.id).url)"
                                        variant="outline"
                                        size="icon"
                                        aria-label="Редактировать"
                                    >
                                        <Pen class="w-4 h-4" />
                                    </Button>
                                    
                                    <AlertDialog>
                                        <AlertDialogTrigger as-child>
                                            <Button
                                                variant="outline"
                                                size="icon"
                                                aria-label="Удалить"
                                            >
                                                <Trash class="w-4 h-4" />
                                            </Button>
                                        </AlertDialogTrigger>
                                        
                                        <AlertDialogContent>
                                            <AlertDialogHeader>
                                                <AlertDialogTitle>
                                                    Удалить заявку?
                                                </AlertDialogTitle>
                                                <AlertDialogDescription>
                                                    Вы уверены, что хотите удалить эту заявку? Это действие нельзя отменить.
                                                </AlertDialogDescription>
                                            </AlertDialogHeader>
                                            
                                            <AlertDialogFooter>
                                                <AlertDialogCancel>
                                                    Отмена
                                                </AlertDialogCancel>
                                                <AlertDialogAction @click="router.delete(ticketsDestroy(ticket.id).url)">
                                                    Удалить
                                                </AlertDialogAction>
                                            </AlertDialogFooter>
                                        </AlertDialogContent>
                                    </AlertDialog>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>