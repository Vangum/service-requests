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
import {tasksCreate, tasksDestroy, tasksEdit} from "@/routes";
import {Button} from "@/components/ui/button";
import {Badge} from "@/components/ui/badge";
import {HoverCard, HoverCardContent, HoverCardTrigger} from "@/components/ui/hover-card";

interface Task {
    id: number;
    description: string;
    location: string;
    scheduled_at_formatted: string;
    status: 'new' | 'completed' | 'rejected';
    completion_notes?: string;
    assignee?: {
        id: number;
        name: string;
    };
}

const {tasks} = defineProps<{
    tasks: Task[];
}>();

const statusLabels: Record<string, string> = {
    'new': 'Запланировано',
    'completed': 'Завершена',
    'rejected': 'Отменена'
};
</script>

<template>
    <Head title="Мои заявки" />
    
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            
            <!-- Если нет событий -->
            <div v-if="tasks.length === 0" class="flex items-center justify-center flex-1">
                <p>Список запланированных событий пуст.
                    <Link :href="tasksCreate().url" class="underline">Добавить</Link>
                </p>
            </div>
            
            <div v-else>
                <div class="flex items-center justify-between gap-8 py-4">
                    <div class="max-w-2xl">
                        <h4 class="font-medium">Запланированные события</h4>
                        
                        <p class="mt-1 text-sm text-muted-foreground leading-relaxed">
                            Здесь отображаются события, которые вы создали и назначили лаборантам.
                        </p>
                    </div>
                    
                    <Button @click="router.get(tasksCreate().url)" class="shrink-0">Запланировать событие</Button>
                </div>
                <!-- Таблица -->
                <div class="rounded-md border">
                    <Table class="w-full table-fixed">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[50px]">ID</TableHead>
                                <TableHead class="w-[200px]">Задача</TableHead>
                                <TableHead>Место проведения</TableHead>
                                <TableHead>Дата и время</TableHead>
                                <TableHead>Лаборант</TableHead>
                                <TableHead>Статус</TableHead>
                                <TableHead class="w-[200px]">Заметки о выполнении</TableHead>
                                <TableHead class="w-[120px] text-right">Действия</TableHead>
                            </TableRow>
                        </TableHeader>
                        
                        <TableBody>
                            <TableRow v-for="task in tasks" :key="task.id">
                                <TableCell class="align-middle">
                                    {{ task.id }}
                                </TableCell>
                                
                                <!-- Описание задачи -->
                                <TableCell class="align-middle">
                                    <HoverCard v-if="task.description.length >= 20">
                                        <HoverCardTrigger as-child>
                                            <div class="truncate cursor-pointer">
                                                {{ task.description }}
                                            </div>
                                        </HoverCardTrigger>
                                        
                                        <HoverCardContent class="w-96">
                                            <p class="text-sm whitespace-normal break-words">
                                                {{ task.description }}
                                            </p>
                                        </HoverCardContent>
                                    </HoverCard>
                                    
                                    <div v-else class="truncate">
                                        {{ task.description }}
                                    </div>
                                </TableCell>
                                
                                <!-- Место проведения -->
                                <TableCell class="align-middle">
                                    {{ task.location }}
                                </TableCell>
                                
                                <!-- Дата и время -->
                                <TableCell class="align-middle">
                                    {{ task.scheduled_at_formatted }}
                                </TableCell>
                                
                                <!-- Лаборант -->
                                <TableCell class="align-middle">
                                    {{ task.assignee?.name ?? '—' }}
                                </TableCell>
                                
                                <!-- Статус -->
                                <TableCell class="align-middle">
                                    <Badge
                                        :variant="task.status === 'new' ? 'outline' : 'secondary'"
                                    >
                                        {{ statusLabels[task.status] }}
                                    </Badge>
                                </TableCell>
                                
                                <!-- Примечания -->
                                <TableCell class="align-middle">
                                    <HoverCard v-if="task.completion_notes && task.completion_notes.length >= 50">
                                        <HoverCardTrigger as-child>
                                            <div class="truncate cursor-pointer">
                                                {{ task.completion_notes }}
                                            </div>
                                        </HoverCardTrigger>
                                        
                                        <HoverCardContent class="w-96">
                                            <p class="text-sm whitespace-normal break-words">
                                                {{ task.completion_notes }}
                                            </p>
                                        </HoverCardContent>
                                    </HoverCard>
                                    
                                    <div v-else class="truncate">
                                        {{ task.completion_notes ?? '–' }}
                                    </div>
                                </TableCell>
                                
                                <TableCell class="align-middle text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button
                                            @click="router.get(tasksEdit(task.id).url)"
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
                                                        Удалить событие?
                                                    </AlertDialogTitle>
                                                    <AlertDialogDescription>
                                                        Вы уверены, что хотите удалить это событие? Это действие нельзя отменить.
                                                    </AlertDialogDescription>
                                                </AlertDialogHeader>
                                                
                                                <AlertDialogFooter>
                                                    <AlertDialogCancel>
                                                        Отмена
                                                    </AlertDialogCancel>
                                                    <AlertDialogAction @click="router.delete(tasksDestroy(task.id).url)">
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
        </div>
    </AppLayout>
</template>