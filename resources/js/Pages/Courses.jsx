import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import SiteLayout from '../Layouts/SiteLayout';
import { badgeColorForCategory } from '../lib/courseBadge';
import { url } from '../lib/url';

const feeIcon = {
    free: 'payments',
    scholarship: 'card_giftcard',
    paid: 'payments',
};

function CourseCard({ course }) {
    const isHighlighted = course.fee_type === 'free' || course.fee_type === 'scholarship';

    return (
        <article className="group bg-surface-container-lowest rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col h-full border border-outline-variant/30">
            <div className="relative w-full h-48 bg-surface-container-high overflow-hidden">
                <img className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src={course.image_url} alt={course.title} />
                <div className={`absolute top-4 left-4 px-3 py-1 rounded-full font-label-caps text-[10px] tracking-wider uppercase font-bold ${badgeColorForCategory(course.category)}`}>
                    {course.badge_label}
                </div>
            </div>
            <div className="p-6 flex flex-col flex-grow">
                <h3 className="font-headline-lg text-[22px] text-on-surface mb-2 font-bold leading-tight">{course.title}</h3>
                <p className="font-body-md text-[14px] text-on-surface-variant line-clamp-3 mb-6 flex-grow">{course.description}</p>
                <div className="flex items-center justify-between mt-auto pt-4 border-t border-outline-variant/20">
                    <div className={`flex items-center gap-1 ${isHighlighted ? 'text-academic-maroon' : 'text-on-surface-variant'}`}>
                        <span className="material-symbols-outlined text-[18px]">{course.icon || feeIcon[course.fee_type] || 'payments'}</span>
                        <span className={`font-label-caps text-[12px] ${isHighlighted ? 'font-bold' : ''}`}>{course.fee_label ?? course.duration_label}</span>
                    </div>
                    <Link href={url(`/apply?major=${course.id}`)} className="text-monastic-saffron font-label-caps text-[12px] hover:text-academic-maroon font-bold flex items-center gap-1">
                        ລົງທະບຽນຮຽນ <span className="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </Link>
                </div>
            </div>
        </article>
    );
}

export default function Courses({ courses, categories }) {
    const [selected, setSelected] = useState('all');
    const filterOptions = [{ value: 'all', label: 'ທັງໝົດ' }, ...categories.map((c) => ({ value: c.slug, label: c.name }))];
    const visible = selected === 'all' ? courses : courses.filter((c) => c.category === selected);

    return (
        <SiteLayout>
            <Head title="ຫຼັກສູດ" />
            <div className="flex flex-col w-full">
                <div className="relative w-full overflow-hidden bg-surface-container py-12">
                    <div className="max-w-container-max mx-auto px-margin-mobile text-center">
                        <span className="font-label-caps text-academic-maroon mb-2 tracking-widest uppercase font-bold block">ACADEMIC PROGRAMS</span>
                        <h1 className="font-headline-display text-4xl md:text-5xl text-on-surface mb-4 font-bold">ຫຼັກສູດການຮຽນ-ການສອນ</h1>
                        <p className="font-body-lg text-on-surface-variant max-w-2xl mx-auto">
                            ພັດທະນາຄວາມຮູ້ທາງດ້ານພຸດທະສາດ, ພາສາບາລີ, ແລະ ວິຊາສາມັນ ເພື່ອສ້າງບຸກຄະລາກອນທີ່ມີຄຸນນະທຳ ແລະ ປັນຍາ ຮັບໃຊ້ສັງຄົມ.
                        </p>
                    </div>
                </div>

                <div className="max-w-container-max mx-auto px-margin-mobile w-full py-12">
                    <div className="flex flex-col lg:flex-row gap-gutter">
                        <aside className="w-full lg:w-1/4 flex-shrink-0">
                            <div className="flex flex-col gap-6 sticky top-24">
                                <div className="bg-surface-container-low rounded-xl p-6 shadow-sm border border-outline-variant/30">
                                    <h2 className="font-headline-lg text-lg text-on-surface mb-4 border-b border-surface-variant pb-2 font-bold">ໝວດໝູ່ຫຼັກສູດ</h2>
                                    <div className="flex flex-col gap-3">
                                        {filterOptions.map((c) => (
                                            <label key={c.value} className="flex items-center gap-3 cursor-pointer group">
                                                <input
                                                    type="radio"
                                                    name="category"
                                                    value={c.value}
                                                    checked={selected === c.value}
                                                    onChange={() => setSelected(c.value)}
                                                    className="w-4 h-4 text-monastic-saffron focus:ring-monastic-saffron border-outline"
                                                />
                                                <span className="font-body-md text-on-surface-variant group-hover:text-monastic-saffron transition-colors font-medium">{c.label}</span>
                                            </label>
                                        ))}
                                    </div>
                                </div>

                                <Link
                                    href={url('/students')}
                                    className="bg-surface-container-low rounded-xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-3 hover:border-monastic-saffron/50 hover:shadow-md transition-all group"
                                >
                                    <div className="w-10 h-10 rounded-full bg-monastic-saffron/10 text-monastic-saffron flex items-center justify-center shrink-0 group-hover:bg-monastic-saffron group-hover:text-on-primary transition-colors">
                                        <span className="material-symbols-outlined">group</span>
                                    </div>
                                    <div>
                                        <h3 className="font-headline-lg text-[15px] text-on-surface font-bold leading-tight">ນັກສຶກສາທີ່ສະໝັກເຂົ້າຮຽນ</h3>
                                        <p className="font-body-md text-[12px] text-on-surface-variant">ເບິ່ງລາຍຊື່ນັກສຶກສາ</p>
                                    </div>
                                </Link>
                            </div>
                        </aside>

                        <div className="w-full lg:w-3/4">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                                {visible.map((course) => (
                                    <CourseCard key={course.id} course={course} />
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SiteLayout>
    );
}
