import { useMemo, useState } from 'react';
import { Head } from '@inertiajs/react';
import SiteLayout from '../Layouts/SiteLayout';
import EventRegisterModal from '../Components/EventRegisterModal';

const categories = [
    { value: 'all', label: 'ທັງໝົດ' },
    { value: 'religious', label: 'ພິທີທາງສາສະໜາ' },
    { value: 'academic', label: 'ສຳມະນາວິຊາການ' },
    { value: 'social', label: 'ກິດຈະກຳເພື່ອສັງຄົມ' },
];

const categoryBadgeColors = {
    religious: 'bg-academic-maroon text-on-primary',
    academic: 'bg-tertiary text-on-tertiary',
    social: 'bg-monastic-saffron text-on-primary',
};

const laoMonths = ['ມັງກອນ', 'ກຸມພາ', 'ມີນາ', 'ເມສາ', 'ພຶດສະພາ', 'ມິຖຸນາ', 'ກໍລະກົດ', 'ສິງຫາ', 'ກັນຍາ', 'ຕຸລາ', 'ພະຈິກ', 'ທັນວາ'];
const weekdayLabels = ['ອາ', 'ຈ', 'ອ', 'ພ', 'ພຫ', 'ສ', 'ສ'];

function EventCard({ event, onRegister }) {
    const date = new Date(event.event_date);
    return (
        <div className="group flex flex-col bg-surface-container-lowest rounded-xl shadow-md border border-outline-variant/30 overflow-hidden hover:shadow-lg transition-all">
            <div className="relative h-48 overflow-hidden">
                <img className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src={event.image_url} alt={event.title} />
                <div className={`absolute top-3 right-3 px-3 py-1 rounded-full font-label-caps text-[10px] tracking-wider font-bold ${categoryBadgeColors[event.category] ?? categoryBadgeColors.social}`}>
                    {event.badge_label}
                </div>
            </div>
            <div className="p-6 flex flex-col flex-grow">
                <div className="flex items-baseline gap-3 mb-3">
                    <div className="text-center">
                        <span className="block font-headline-lg text-[22px] text-academic-maroon leading-none font-bold">{String(date.getDate()).padStart(2, '0')}</span>
                        <span className="block font-label-caps text-[10px] text-on-surface-variant uppercase mt-1">{laoMonths[date.getMonth()]}</span>
                    </div>
                    <h3 className="font-headline-lg text-[18px] text-on-surface leading-tight font-bold group-hover:text-academic-maroon transition-colors">{event.title}</h3>
                </div>
                <p className="font-body-md text-[14px] text-on-surface-variant line-clamp-2 mb-4 flex-grow">{event.description}</p>
                <div className="flex items-center gap-2 text-on-surface-variant font-body-md text-[12px] pb-4 border-b border-surface-variant">
                    <span className="material-symbols-outlined text-[14px] text-monastic-saffron">location_on</span>
                    <span>{event.location}</span>
                </div>
                {event.registration_url ? (
                    <a
                        href={event.registration_url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="mt-4 w-full text-center px-5 py-2 bg-monastic-saffron text-on-primary rounded-lg font-label-caps text-[13px] font-bold hover:bg-academic-maroon transition-colors shadow-sm flex items-center justify-center gap-1"
                    >
                        <span>ລົງທະບຽນ</span>
                        <span className="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                ) : (
                    <button
                        onClick={() => onRegister(event)}
                        className="mt-4 w-full text-center px-5 py-2 bg-monastic-saffron text-on-primary rounded-lg font-label-caps text-[13px] font-bold hover:bg-academic-maroon transition-colors shadow-sm flex items-center justify-center gap-1"
                    >
                        <span>ລົງທະບຽນ</span>
                        <span className="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </button>
                )}
            </div>
        </div>
    );
}

function MiniCalendar({ events }) {
    const [cursor, setCursor] = useState(() => {
        const now = new Date();
        return new Date(now.getFullYear(), now.getMonth(), 1);
    });

    const eventDays = useMemo(() => {
        const set = new Set();
        events.forEach((e) => {
            const d = new Date(e.event_date);
            if (d.getFullYear() === cursor.getFullYear() && d.getMonth() === cursor.getMonth()) {
                set.add(d.getDate());
            }
        });
        return set;
    }, [events, cursor]);

    const firstWeekday = new Date(cursor.getFullYear(), cursor.getMonth(), 1).getDay();
    const daysInMonth = new Date(cursor.getFullYear(), cursor.getMonth() + 1, 0).getDate();
    const daysInPrevMonth = new Date(cursor.getFullYear(), cursor.getMonth(), 0).getDate();
    const today = new Date();
    const isCurrentMonth = today.getFullYear() === cursor.getFullYear() && today.getMonth() === cursor.getMonth();

    const cells = [];
    for (let i = firstWeekday - 1; i >= 0; i--) {
        cells.push({ day: daysInPrevMonth - i, muted: true });
    }
    for (let d = 1; d <= daysInMonth; d++) {
        cells.push({ day: d, muted: false, hasEvent: eventDays.has(d), isToday: isCurrentMonth && d === today.getDate() });
    }
    while (cells.length % 7 !== 0) {
        cells.push({ day: cells.length - (firstWeekday + daysInMonth) + 1, muted: true });
    }

    return (
        <div className="bg-surface-container p-6 rounded-xl shadow-sm border border-outline-variant/30">
            <div className="flex items-center justify-between mb-4">
                <button onClick={() => setCursor(new Date(cursor.getFullYear(), cursor.getMonth() - 1, 1))} className="text-on-surface-variant hover:text-academic-maroon">
                    <span className="material-symbols-outlined">chevron_left</span>
                </button>
                <span className="font-headline-lg text-lg text-on-surface font-bold">{laoMonths[cursor.getMonth()]} {cursor.getFullYear()}</span>
                <button onClick={() => setCursor(new Date(cursor.getFullYear(), cursor.getMonth() + 1, 1))} className="text-on-surface-variant hover:text-academic-maroon">
                    <span className="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
            <div className="grid grid-cols-7 gap-1 text-center mb-2 font-bold text-on-surface-variant text-[11px]">
                {weekdayLabels.map((w, i) => <span key={i}>{w}</span>)}
            </div>
            <div className="grid grid-cols-7 gap-1 text-center font-body-md text-[13px]">
                {cells.map((cell, i) => (
                    <div
                        key={i}
                        className={
                            cell.muted
                                ? 'p-2 text-surface-variant/40'
                                : cell.isToday
                                    ? 'p-2 bg-academic-maroon text-on-primary rounded-full shadow-md font-bold'
                                    : 'p-2 hover:bg-surface-variant rounded-full cursor-pointer relative'
                        }
                    >
                        {cell.day}
                        {!cell.muted && !cell.isToday && cell.hasEvent && (
                            <span className="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-monastic-saffron rounded-full" />
                        )}
                    </div>
                ))}
            </div>
        </div>
    );
}

export default function Events({ featuredEvent, upcomingEvents, allEvents }) {
    const [selected, setSelected] = useState('all');
    const [registerEvent, setRegisterEvent] = useState(null);
    const visibleUpcoming = selected === 'all' ? upcomingEvents : upcomingEvents.filter((e) => e.category === selected);
    const featuredDate = featuredEvent ? new Date(featuredEvent.event_date) : null;

    return (
        <SiteLayout>
            <Head title="ກິດຈະກຳ" />
            <div className="flex flex-col w-full bg-surface">
                <div className="relative w-full overflow-hidden bg-surface-container py-12">
                    <div className="max-w-container-max mx-auto px-margin-mobile text-center">
                        <span className="font-label-caps text-academic-maroon mb-2 tracking-widest uppercase font-bold block">COLLEGE EVENTS &amp; NEWS</span>
                        <h1 className="font-headline-display text-4xl md:text-5xl text-on-surface mb-4 font-bold">ກິດຈະກຳ ແລະ ງານສຳຄັນ</h1>
                        <p className="font-body-lg text-on-surface-variant max-w-2xl mx-auto">
                            ຕິດຕາມກິດຈະກຳທາງສາສະໜາ, ການສຳມະນາທາງວິຊາການ ແລະ ງານບຸນປະເພນີຕ່າງໆ ທີ່ຈັດຂຶ້ນພາຍໃນວິທະຍາໄລ ແລະ ສັງຄົມ.
                        </p>
                    </div>
                </div>

                <div className="max-w-container-max mx-auto px-margin-mobile w-full grid grid-cols-1 lg:grid-cols-12 gap-gutter my-12">
                    {featuredEvent && (
                        <div className="lg:col-span-8 flex flex-col group relative overflow-hidden rounded-xl shadow-md bg-surface-container border border-outline-variant/30">
                            <div className="relative w-full h-[300px] md:h-[380px] overflow-hidden">
                                <img className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src={featuredEvent.image_url} alt={featuredEvent.title} />
                                <div className="absolute top-4 left-4 bg-academic-maroon text-on-primary px-4 py-2 rounded-lg backdrop-blur-md shadow-md">
                                    <span className="font-headline-lg text-[24px] font-bold block leading-none">{String(featuredDate.getDate()).padStart(2, '0')}</span>
                                    <span className="font-label-caps text-[10px] uppercase font-bold">{laoMonths[featuredDate.getMonth()]} {featuredDate.getFullYear()}</span>
                                </div>
                            </div>
                            <div className="p-6 relative z-10 bg-surface-container flex flex-col justify-between flex-grow">
                                <div>
                                    <div className="flex items-center gap-2 mb-2 text-monastic-saffron">
                                        <span className="material-symbols-outlined text-[18px]">verified</span>
                                        <span className="font-label-caps uppercase tracking-wider font-bold text-[12px]">{featuredEvent.badge_label}</span>
                                    </div>
                                    <h2 className="font-headline-lg text-2xl text-on-surface mb-3 font-bold group-hover:text-academic-maroon transition-colors">{featuredEvent.title}</h2>
                                    <p className="font-body-md text-on-surface-variant line-clamp-3 mb-4 leading-relaxed">{featuredEvent.description}</p>
                                </div>
                                <div className="flex items-center justify-between mt-4 border-t border-outline-variant/30 pt-4">
                                    <div className="flex flex-col gap-1">
                                        {featuredEvent.start_time && (
                                            <div className="flex items-center gap-2 text-on-surface-variant font-body-md text-[14px]">
                                                <span className="material-symbols-outlined text-[16px] text-monastic-saffron">schedule</span>
                                                <span>{featuredEvent.start_time}{featuredEvent.end_time ? ` - ${featuredEvent.end_time}` : ''}</span>
                                            </div>
                                        )}
                                        <div className="flex items-center gap-2 text-on-surface-variant font-body-md text-[14px]">
                                            <span className="material-symbols-outlined text-[16px] text-monastic-saffron">location_on</span>
                                            <span>{featuredEvent.location}</span>
                                        </div>
                                    </div>
                                    {featuredEvent.registration_url ? (
                                        <a
                                            href={featuredEvent.registration_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="px-5 py-2.5 bg-monastic-saffron text-on-primary rounded-lg font-label-caps font-bold hover:bg-academic-maroon transition-colors shadow-sm flex items-center gap-1"
                                        >
                                            <span>ລົງທະບຽນ</span>
                                            <span className="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        </a>
                                    ) : (
                                        <button onClick={() => setRegisterEvent(featuredEvent)} className="px-5 py-2.5 bg-monastic-saffron text-on-primary rounded-lg font-label-caps font-bold hover:bg-academic-maroon transition-colors shadow-sm flex items-center gap-1">
                                            <span>ລົງທະບຽນ</span>
                                            <span className="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        </button>
                                    )}
                                </div>
                            </div>
                        </div>
                    )}

                    <div className="lg:col-span-4 flex flex-col gap-6">
                        <div className="bg-surface-container-low p-6 rounded-xl shadow-sm border border-outline-variant/30">
                            <h3 className="font-label-caps text-academic-maroon uppercase tracking-wider mb-4 border-b border-outline-variant/20 pb-2 font-bold">ປະເພດກິດຈະກຳ</h3>
                            <div className="flex flex-col gap-3">
                                {categories.map((c) => (
                                    <label key={c.value} className="flex items-center gap-3 cursor-pointer group">
                                        <div
                                            onClick={() => setSelected(c.value)}
                                            className={
                                                'w-5 h-5 rounded flex items-center justify-center shadow-sm ' +
                                                (selected === c.value ? 'bg-academic-maroon text-on-primary' : 'bg-surface-variant border border-outline-variant')
                                            }
                                        >
                                            {selected === c.value && <span className="material-symbols-outlined text-[14px]">check</span>}
                                        </div>
                                        <span
                                            onClick={() => setSelected(c.value)}
                                            className={
                                                'font-body-md font-medium group-hover:text-academic-maroon transition-colors ' +
                                                (selected === c.value ? 'text-on-surface' : 'text-on-surface-variant')
                                            }
                                        >
                                            {c.label}
                                        </span>
                                    </label>
                                ))}
                            </div>
                        </div>

                        <MiniCalendar events={allEvents} />
                    </div>
                </div>

                <div className="max-w-container-max mx-auto px-margin-mobile w-full mb-16">
                    <div className="flex items-end justify-between mb-8 border-b border-outline-variant/30 pb-4">
                        <div>
                            <span className="font-label-caps text-monastic-saffron uppercase tracking-widest block mb-1 font-bold">UPCOMING</span>
                            <h2 className="font-headline-lg text-3xl text-on-surface font-bold">ກິດຈະກຳຈະມາເຖິງ</h2>
                        </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                        {visibleUpcoming.map((event) => (
                            <EventCard key={event.id} event={event} onRegister={setRegisterEvent} />
                        ))}
                    </div>
                </div>
            </div>

            <EventRegisterModal event={registerEvent} onClose={() => setRegisterEvent(null)} />
        </SiteLayout>
    );
}
