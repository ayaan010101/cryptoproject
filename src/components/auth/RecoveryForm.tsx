import React, { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import { Mail, Shield, CreditCard, ArrowRight } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
  CardFooter,
} from "@/components/ui/card";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Alert, AlertTitle, AlertDescription } from "@/components/ui/alert";
import { Progress } from "@/components/ui/progress";

const formSchema = z.object({
  email: z.string().email({ message: "Please enter a valid email address" }),
  identityVerification: z
    .string()
    .min(6, { message: "Verification code must be at least 6 characters" }),
  usdtAmount: z
    .string()
    .refine((val) => !isNaN(Number(val)) && Number(val) >= 10, {
      message: "Payment must be at least 10 USDT",
    }),
});

type RecoveryFormProps = {
  onComplete?: (data: z.infer<typeof formSchema>) => void;
  isOpen?: boolean;
};

const RecoveryForm = ({
  onComplete = () => {},
  isOpen = true,
}: RecoveryFormProps) => {
  const [step, setStep] = useState<number>(1);
  const [isProcessing, setIsProcessing] = useState<boolean>(false);

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: {
      email: "",
      identityVerification: "",
      usdtAmount: "10",
    },
  });

  const handleNextStep = async () => {
    if (step === 1) {
      const emailValid = await form.trigger("email");
      if (emailValid) setStep(2);
    } else if (step === 2) {
      const verificationValid = await form.trigger("identityVerification");
      if (verificationValid) setStep(3);
    } else if (step === 3) {
      const paymentValid = await form.trigger("usdtAmount");
      if (paymentValid) {
        setIsProcessing(true);
        // Simulate payment processing
        setTimeout(() => {
          setIsProcessing(false);
          setStep(4);
        }, 2000);
      }
    }
  };

  const onSubmit = (data: z.infer<typeof formSchema>) => {
    onComplete(data);
  };

  if (!isOpen) return null;

  return (
    <Card className="w-full max-w-md mx-auto bg-white shadow-lg">
      <CardHeader>
        <CardTitle className="text-xl font-bold text-center">
          Security Key Recovery
        </CardTitle>
        <CardDescription className="text-center">
          Recover your security key by completing the verification process
        </CardDescription>
        <div className="mt-4">
          <Progress value={(step / 4) * 100} className="h-2" />
          <div className="flex justify-between mt-1 text-xs text-gray-500">
            <span>Email</span>
            <span>Verify</span>
            <span>Payment</span>
            <span>Complete</span>
          </div>
        </div>
      </CardHeader>
      <CardContent>
        <Form {...form}>
          <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
            {step === 1 && (
              <div className="space-y-4">
                <FormField
                  control={form.control}
                  name="email"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>Email Address</FormLabel>
                      <div className="relative">
                        <Mail className="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                        <FormControl>
                          <Input
                            placeholder="your.email@example.com"
                            className="pl-10"
                            {...field}
                          />
                        </FormControl>
                      </div>
                      <FormMessage />
                    </FormItem>
                  )}
                />
                <Alert className="bg-blue-50 border-blue-200">
                  <AlertTitle>Verification Required</AlertTitle>
                  <AlertDescription>
                    We'll send a verification code to this email to confirm your
                    identity.
                  </AlertDescription>
                </Alert>
              </div>
            )}

            {step === 2 && (
              <div className="space-y-4">
                <FormField
                  control={form.control}
                  name="identityVerification"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>Identity Verification Code</FormLabel>
                      <div className="relative">
                        <Shield className="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                        <FormControl>
                          <Input
                            placeholder="Enter verification code"
                            className="pl-10"
                            {...field}
                          />
                        </FormControl>
                      </div>
                      <FormMessage />
                    </FormItem>
                  )}
                />
                <Alert className="bg-yellow-50 border-yellow-200">
                  <AlertTitle>Security Notice</AlertTitle>
                  <AlertDescription>
                    For your protection, key recovery requires a USDT payment in
                    the next step.
                  </AlertDescription>
                </Alert>
              </div>
            )}

            {step === 3 && (
              <div className="space-y-4">
                <FormField
                  control={form.control}
                  name="usdtAmount"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>USDT Payment Amount</FormLabel>
                      <div className="relative">
                        <CreditCard className="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                        <FormControl>
                          <Input
                            type="number"
                            min="10"
                            placeholder="10"
                            className="pl-10"
                            {...field}
                          />
                        </FormControl>
                      </div>
                      <FormMessage />
                    </FormItem>
                  )}
                />
                <Alert className="bg-green-50 border-green-200">
                  <AlertTitle>Payment Information</AlertTitle>
                  <AlertDescription>
                    A minimum payment of 10 USDT is required to generate a new
                    security key.
                  </AlertDescription>
                </Alert>
              </div>
            )}

            {step === 4 && (
              <div className="space-y-4 text-center">
                <div className="p-4 rounded-full bg-green-100 mx-auto w-16 h-16 flex items-center justify-center">
                  <Shield className="h-8 w-8 text-green-600" />
                </div>
                <h3 className="text-lg font-medium">Recovery Successful!</h3>
                <p className="text-gray-500">
                  Your new security key has been generated and sent to your
                  email address.
                </p>
                <Button type="submit" className="w-full">
                  Complete Recovery
                </Button>
              </div>
            )}

            {step < 4 && (
              <Button
                type="button"
                onClick={handleNextStep}
                disabled={isProcessing}
                className="w-full flex items-center justify-center"
              >
                {isProcessing ? (
                  <span>Processing...</span>
                ) : (
                  <>
                    {step === 3 ? "Make Payment" : "Continue"}
                    <ArrowRight className="ml-2 h-4 w-4" />
                  </>
                )}
              </Button>
            )}
          </form>
        </Form>
      </CardContent>
      <CardFooter className="flex justify-center border-t pt-4">
        <p className="text-xs text-gray-500">
          Need help? Contact our support team at support@cryptoplatform.com
        </p>
      </CardFooter>
    </Card>
  );
};

export default RecoveryForm;
