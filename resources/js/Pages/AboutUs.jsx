import { Fragment, useState } from 'react';
import { Head } from '@inertiajs/react';
import SiteLayout from '../Layouts/SiteLayout';
import StatCounter from '../Components/StatCounter';
import DepartmentStaffModal from '../Components/DepartmentStaffModal';

export default function AboutUs({ stats, missionPillars, orgRoot, gallery }) {
    const [selectedDept, setSelectedDept] = useState(null);

    return (
        <SiteLayout>
            <Head title="ກ່ຽວກັບພວກເຮົາ" />
            <div className="flex flex-col w-full">
                {/* Hero */}
                <section className="relative w-full h-[60vh] min-h-[400px] flex items-center justify-center -mt-20 overflow-hidden bg-on-primary-fixed">
                    <div
                        className="absolute inset-0 bg-cover bg-center w-full h-full mix-blend-overlay opacity-60"
                        style={{ backgroundImage: "url('https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=1600&q=80')" }}
                    />
                    <div className="absolute inset-0 bg-gradient-to-b from-on-primary-fixed/80 via-on-primary-fixed/40 to-surface" />
                    <div className="relative z-10 max-w-container-max mx-auto px-margin-mobile text-center pt-20">
                        <span className="font-label-caps text-monastic-saffron tracking-widest uppercase mb-stack-md block">ປະຫວັດຄວາມເປັນມາ ແລະ ວິໄສທັດ</span>
                        <h1 className="font-headline-display text-headline-display text-on-primary mb-stack-lg max-w-3xl mx-auto">ກ່ຽວກັບ ວິທະຍາໄລຄູສົງ ອົງຕື້</h1>
                        <p className="font-body-lg text-body-lg text-surface-variant max-w-2xl mx-auto">
                            ສູນກາງການສຶກສາພຸດທະສາດ ແລະ ວັດທະນະທຳລາວ ທີ່ເປັນເລີດທາງດ້ານວິຊາການ ແລະ ຄຸນນະທຳ ສ້າງສາພັດທະນາບຸກຄະລາກອນທາງພະພຸດທະສາສະໜາໃຫ້ມີຄວາມຮູ້ຄູ່ຄຸນນະທຳ.
                        </p>
                    </div>
                </section>

                {/* History */}
                <section className="max-w-container-max mx-auto px-margin-mobile py-section-gap relative">
                    <div className="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
                        <div className="lg:col-span-5 relative z-10">
                            <div className="bg-surface-container-low p-stack-lg shadow-xl relative">
                                <div className="absolute -top-4 -left-4 w-24 h-24 bg-academic-maroon/10 -z-10 rounded-full blur-xl" />
                                <h2 className="font-headline-lg text-headline-lg text-academic-maroon mb-stack-md">ປະຫວັດຄວາມເປັນມາ</h2>
                                <p className="font-body-md text-body-md text-on-surface-variant mb-stack-md">
                                    ວິທະຍາໄລຄູສົງ ອົງຕື້ ໄດ້ຮັບການສ້າງຕັ້ງຂຶ້ນເພື່ອເປັນສະຖານທີ່ສຶກສາ ແລະ ຄົ້ນຄວ້າທາງດ້ານພຸດທະສາສະໜາທີ່ສຳຄັນຂອງປະເທດລາວ. ໂດຍມີຈຸດປະສົງຫຼັກໃນການສືບທອດພຸດທະສາສະໜາ ແລະ ວັດທະນະທຳອັນດີງາມຂອງຊາດ.
                                </p>
                                <p className="font-body-md text-body-md text-on-surface-variant mb-stack-lg">
                                    ຕະຫຼອດໄລຍະເວລາຫຼາຍທົດສະວັດ, ວິທະຍາໄລໄດ້ພັດທະນາຫຼັກສູດຢ່າງຕໍ່ເນື່ອງ ເພື່ອຕອບສະໜອງຕໍ່ການປ່ຽນແປງຂອງສັງຄົມ ໃນຂະນະທີ່ຍັງຮັກສາແກ່ນແທ້ຂອງຫຼັກທຳຄຳສອນໄວ້ຢ່າງໝັ້ນຄົງ.
                                </p>
                                <div className="flex items-center gap-4 pt-stack-sm border-t border-surface-variant">
                                    {stats.map((s, i) => (
                                        <Fragment key={s.id}>
                                            {i > 0 && <div className="w-px h-12 bg-surface-variant" />}
                                            <StatCounter target={s.value} label={s.title} />
                                        </Fragment>
                                    ))}
                                </div>
                            </div>
                        </div>
                        <div className="lg:col-span-7 relative">
                            <div className="grid grid-cols-2 gap-4">
                                <img className="w-full h-[300px] object-cover shadow-lg rounded-tl-3xl mt-8" alt="ປະຫວັດຄວາມເປັນມາ" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfN9OBsXgf-djVzSxYb11LYjjX0LIsdwPRC5tjUu0-B4ySR5Ie2VB3kBr0cjTvWXfn8CLb-7XNh30bHv8RNJqNF3nMzLQDFsZGL8z6EQTFfWJmILpVaXsZPvSqMp4Hj2lsyYtIUHm022cL1jtiU_nDKS58xHrN3XvgQ--tYcsX6Z2LAwr_G7R6mEOYO7AcTyxEAGtmed6QPMuDMmRMK_KBJ95TgLiRJuouLlLeqP9dnp2AVa8kDT6p9A" />
                                <img className="w-full h-[400px] object-cover shadow-lg -mt-8 rounded-br-3xl" alt="ຊີວິດນັກສຶກສາປັດຈຸບັນ" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA5enDZo4W_yy_5a_5h0Su29KwoqibvrJj6qiqMsM4fnx1OXmsGbcFEnbnhcG-lCYRKLEMAZIGtS7a4yj7yVKgZJfoQikGf5eF7axTAFhYq_8rSDq9cvz1dK8hYSCt3Ehx_wVBVhRb1VBGZmkrvVv9lv2oXAh0eyVGgf58pqKZZWOY-EjriFll8UU07Bd1KP8sk3zmnZUdXiKkkjNEoRkrsEmvqA45wg3WP1JF2YUKBl4Csgg7UU6fgNg" />
                            </div>
                            <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-monastic-saffron/20 rounded-full mix-blend-multiply blur-2xl -z-10" />
                        </div>
                    </div>
                </section>

                {/* Vision & Mission */}
                <section className="bg-surface-container-lowest py-section-gap">
                    <div className="max-w-container-max mx-auto px-margin-mobile">
                        <div className="text-center mb-stack-lg">
                            <h2 className="font-headline-display text-[40px] text-on-surface mb-stack-sm">ວິໄສທັດ ແລະ ພັນທະກິດ</h2>
                            <div className="w-16 h-1 bg-academic-maroon mx-auto" />
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                            <div className="md:col-span-1 bg-academic-maroon text-on-primary p-stack-lg shadow-md relative overflow-hidden group">
                                <div className="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition-transform duration-700">
                                    <span className="material-symbols-outlined text-[150px]">visibility</span>
                                </div>
                                <span className="material-symbols-outlined text-[40px] text-monastic-saffron mb-stack-md block">visibility</span>
                                <h3 className="font-headline-lg text-headline-lg mb-stack-sm">ວິໄສທັດ</h3>
                                <p className="font-body-md text-body-md text-primary-fixed-dim leading-relaxed">
                                    ເປັນສູນກາງການສຶກສາລະດັບຊາດ ໃນການຄົ້ນຄວ້າ, ເຜີຍແຜ່ ແລະ ອະນຸລັກພຸດທະສາສະໜາ ແລະ ວັດທະນະທຳລາວ ໃຫ້ຍືນຍົງຄູ່ສັງຄົມ.
                                </p>
                            </div>
                            <div className="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                                {missionPillars.map((m) => (
                                    <div key={m.id} className="bg-surface p-stack-lg shadow-sm hover:shadow-md transition-shadow">
                                        <span className="material-symbols-outlined text-[32px] text-academic-maroon mb-stack-sm block">{m.icon}</span>
                                        <h4 className="font-label-caps text-[14px] text-on-surface mb-stack-sm font-bold">{m.title}</h4>
                                        <p className="font-body-md text-body-md text-on-surface-variant text-sm">{m.description}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>

                {/* Org Structure */}
                <section className="py-section-gap bg-surface-container">
                    <div className="max-w-container-max mx-auto px-margin-mobile">
                        <h2 className="font-headline-display text-[40px] text-on-surface text-center mb-stack-lg">ໂຄງຮ່າງການຈັດຕັ້ງ</h2>
                        <div className="relative w-full overflow-hidden bg-surface shadow-lg p-stack-lg">
                            <div className="flex flex-col items-center gap-8">
                                {orgRoot && (
                                    <>
                                        <div className="bg-academic-maroon text-on-primary px-8 py-4 text-center min-w-[250px] shadow-md z-10 relative">
                                            <div className="font-label-caps text-[12px] opacity-80 mb-1">{orgRoot.role_label}</div>
                                            <div className="font-headline-lg text-[20px]">{orgRoot.name}</div>
                                        </div>
                                        <div className="w-px h-8 bg-outline-variant -my-8 z-0" />
                                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10 w-full max-w-4xl pt-8">
                                            {orgRoot.children?.map((dept) => (
                                                <div
                                                    key={dept.id}
                                                    onClick={() => setSelectedDept(dept)}
                                                    className="bg-surface-container-lowest border border-outline-variant px-6 py-4 text-center hover:bg-monastic-saffron hover:text-on-primary hover:border-transparent transition-colors cursor-pointer group"
                                                >
                                                    <span className="material-symbols-outlined text-[24px] text-academic-maroon group-hover:text-on-primary mb-2 block">{dept.icon}</span>
                                                    <div className="font-body-md font-semibold">{dept.name}</div>
                                                </div>
                                            ))}
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </section>

                {/* Gallery */}
                <section className="py-section-gap bg-surface-container-lowest">
                    <div className="max-w-container-max mx-auto px-margin-mobile">
                        <div className="flex justify-between items-end mb-stack-lg">
                            <div>
                                <h2 className="font-headline-display text-[40px] text-on-surface mb-stack-sm">ຮູບພາບກິດຈະກຳ</h2>
                                <p className="font-body-md text-surface-variant">ຊີວິດນັກສຶກສາ ແລະ ບັນຍາກາດພາຍໃນວິທະຍາໄລ</p>
                            </div>
                        </div>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {gallery.map((img, i) => (
                                <div
                                    key={img.id}
                                    className={
                                        'relative group overflow-hidden bg-surface-variant ' +
                                        (i === 0 ? 'col-span-2 row-span-2' : i === 3 ? 'col-span-2 h-48 md:h-64' : 'h-48 md:h-64')
                                    }
                                >
                                    <img className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src={img.image_url} alt={img.alt_text} />
                                    {img.caption && (
                                        <div className="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                            <span className="text-white font-body-md">{img.caption}</span>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            </div>

            <DepartmentStaffModal department={selectedDept} onClose={() => setSelectedDept(null)} />
        </SiteLayout>
    );
}
