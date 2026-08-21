import { useEffect, useRef, useState } from 'react';

export default function StatCounter({ target, label }) {
    const ref = useRef(null);
    const [value, setValue] = useState('0');

    useEffect(() => {
        const isPlus = target.startsWith('+') || target.endsWith('+');
        const numeric = parseInt(target.replace(/[^0-9]/g, ''), 10);
        const duration = 2000;
        const step = numeric / (duration / 16);
        let current = 0;
        let raf;

        const animate = () => {
            current += step;
            if (current < numeric) {
                setValue(Math.ceil(current).toLocaleString());
                raf = requestAnimationFrame(animate);
            } else {
                setValue((isPlus ? '+' : '') + numeric.toLocaleString());
            }
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animate();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        if (ref.current) observer.observe(ref.current);
        return () => {
            observer.disconnect();
            if (raf) cancelAnimationFrame(raf);
        };
    }, [target]);

    return (
        <div className="text-center">
            <div ref={ref} className="font-headline-lg text-[32px] text-monastic-saffron font-bold">{value}</div>
            <div className="font-label-caps text-label-caps text-text-muted">{label}</div>
        </div>
    );
}
