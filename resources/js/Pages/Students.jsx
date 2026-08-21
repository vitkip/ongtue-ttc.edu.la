import { Head } from '@inertiajs/react';
import SiteLayout from '../Layouts/SiteLayout';

function StudentRow({ student }) {
    return (
        <div className="flex items-center gap-4 p-4 hover:bg-surface-container transition-colors">
            <div className="w-14 h-14 rounded-full bg-surface-container-high overflow-hidden shrink-0 border border-outline-variant/30">
                <img className="w-full h-full object-cover" src={student.photo_url} alt={student.full_name} />
            </div>
            <div className="flex flex-col min-w-0">
                <h3 className="font-headline-lg text-[16px] text-on-surface font-bold leading-tight truncate">{student.full_name}</h3>
                {student.major && (
                    <p className="font-body-md text-[13px] text-academic-maroon font-medium truncate">{student.major}</p>
                )}
            </div>
            {student.academic_year && (
                <span className="ml-auto shrink-0 font-label-caps text-[11px] text-on-surface-variant bg-surface-container-high px-3 py-1 rounded-full">
                    ປີການສຶກສາ {student.academic_year}
                </span>
            )}
        </div>
    );
}

export default function Students({ students }) {
    return (
        <SiteLayout>
            <Head title="ນັກສຶກສາ" />
            <div className="flex flex-col w-full">
                <div className="relative w-full overflow-hidden bg-surface-container py-12">
                    <div className="max-w-container-max mx-auto px-margin-mobile text-center">
                        <span className="font-label-caps text-academic-maroon mb-2 tracking-widest uppercase font-bold block">OUR STUDENTS</span>
                        <h1 className="font-headline-display text-4xl md:text-5xl text-on-surface mb-4 font-bold">ນັກສຶກສາທີ່ສະໝັກເຂົ້າຮຽນ</h1>
                        <p className="font-body-lg text-on-surface-variant max-w-2xl mx-auto">
                            ຮູ້ຈັກກັບນັກສຶກສາທີ່ໄດ້ສະໝັກເຂົ້າຮຽນຢູ່ວິທະຍາໄລຄູສົງ ອົງຕື້.
                        </p>
                    </div>
                </div>

                <div className="max-w-3xl mx-auto px-margin-mobile w-full py-12">
                    {students.length === 0 ? (
                        <div className="text-center py-16">
                            <span className="material-symbols-outlined text-[48px] text-on-surface-variant opacity-50">group</span>
                            <p className="font-body-md text-on-surface-variant mt-4">ຍັງບໍ່ມີຂໍ້ມູນນັກສຶກສາໃນຂະນະນີ້</p>
                        </div>
                    ) : (
                        <div className="bg-surface-container-lowest rounded-xl shadow-md border border-outline-variant/30 divide-y divide-outline-variant/20 overflow-hidden">
                            {students.map((student) => (
                                <StudentRow key={student.id} student={student} />
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </SiteLayout>
    );
}
