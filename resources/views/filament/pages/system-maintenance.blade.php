<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">ເຄື່ອງມືບຳລຸງຮັກສາ</x-slot>
            <x-slot name="description">
                ໃຊ້ປຸ່ມຢູ່ມุมຂວາເທິງເພື່ອລ້າງ cache ຫຼັງຈາກແກ້ໄຂການຕັ້ງຄ່າ ຫຼື ເນື້ອຫາ.
            </x-slot>

            <dl class="grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ລ້າງ Cache ທັງໝົດ</dt>
                    <dd class="mt-1 text-sm text-gray-950 dark:text-white">
                        ລ້າງ config / route / view / application cache ແລະ cache ຂອງ Filament. ໃຊ້ຫຼັງແກ້ໄຂການຕັ້ງຄ່າ ຫຼື ເມื່ອໜ້າ admin ສະແດງຜົນຜິດປົກກະຕິ.
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ລ້າງ View Cache</dt>
                    <dd class="mt-1 text-sm text-gray-950 dark:text-white">
                        ລ້າງແຕ່ compiled Blade views. ໄວທີ່ສຸດ, ໃຊ້ເມื່ອໜ້າເວັບຄ້າງຢູ່ layout ເກົ່າ.
                    </dd>
                </div>
            </dl>
        </x-filament::section>

        @if ($lastOutput !== null)
            <x-filament::section>
                <x-slot name="heading">
                    ຜົນລັບຫຼ້າສຸດ: {{ $lastTitle }}
                </x-slot>
                <x-slot name="description">
                    <span class="inline-flex items-center gap-2">
                        @if ($lastStatus === 'success')
                            <x-filament::badge color="success">ສຳເລັດ</x-filament::badge>
                        @else
                            <x-filament::badge color="danger">ລົ້ມເຫລວ</x-filament::badge>
                        @endif
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $lastFinishedAt }}</span>
                    </span>
                </x-slot>

                <pre class="overflow-x-auto rounded-lg bg-gray-950 p-4 text-xs leading-relaxed text-gray-100 dark:bg-black"><code>{{ $lastOutput }}</code></pre>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
