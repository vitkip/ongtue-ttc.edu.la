export default function DepartmentStaffModal({ department, onClose }) {
    if (!department) return null;

    const staff = department.staff_members ?? [];

    return (
        <div
            className="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            onClick={(e) => e.target === e.currentTarget && onClose()}
        >
            <div className="bg-surface rounded-2xl max-w-2xl w-full max-h-[85vh] overflow-y-auto p-6 md:p-8 shadow-2xl relative border border-outline-variant/30">
                <button onClick={onClose} className="absolute top-4 right-4 text-on-surface-variant hover:text-academic-maroon">
                    <span className="material-symbols-outlined text-[24px]">close</span>
                </button>
                <div className="flex items-center gap-3 mb-6">
                    <div className="w-10 h-10 rounded-full bg-monastic-saffron/20 text-monastic-saffron flex items-center justify-center">
                        <span className="material-symbols-outlined">{department.icon}</span>
                    </div>
                    <div>
                        <h3 className="font-headline-lg text-[22px] text-academic-maroon leading-tight">{department.name}</h3>
                        {department.role_label && (
                            <p className="font-label-caps text-[11px] text-on-surface-variant">{department.role_label}</p>
                        )}
                    </div>
                </div>

                {staff.length === 0 ? (
                    <p className="font-body-md text-on-surface-variant text-center py-8">ຍັງບໍ່ມີຂໍ້ມູນບຸກຄະລາກອນ</p>
                ) : (
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {staff.map((member) => (
                            <div key={member.id} className="flex items-center gap-4 p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/30">
                                <div className="w-14 h-14 rounded-full overflow-hidden bg-surface-variant flex-shrink-0 flex items-center justify-center">
                                    {member.resolved_photo_url ? (
                                        <img src={member.resolved_photo_url} alt={member.full_name} className="w-full h-full object-cover" />
                                    ) : (
                                        <span className="material-symbols-outlined text-[28px] text-on-surface-variant">person</span>
                                    )}
                                </div>
                                <div>
                                    <div className="font-body-md font-semibold text-on-surface">{member.full_name}</div>
                                    {member.title && <div className="font-body-md text-[13px] text-on-surface-variant">{member.title}</div>}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}
